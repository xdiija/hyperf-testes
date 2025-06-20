<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helper\ValidationHelper;
use App\Service\RetroService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use App\Support\Response;

class RetroController extends AbstractController
{
    protected Response $response;
    private RetroService $retroService;
    protected StdoutLoggerInterface $logger;

    public function __construct(
        RetroService $retroService,
        StdoutLoggerInterface $logger,
        Response $response,
    ) {
        $this->retroService = $retroService;
        $this->logger = $logger;
        $this->response = $response;
    }

    public function index(RequestInterface $request)
    {
        return $this->retroService->getAll();
    }

    public function show(string $slug)
    {
        return $this->retroService->getRetroBySlug($slug);
    }

    public function store(RequestInterface $request)
    {
        $validator = new ValidationHelper($request->all(), $this->logger);
        $validator
            ->field('title', 'Título')->required()->isString()->maxLen(100)
            ->field('squad_id', 'Squad')->required()->numeric()
            ->field('date', 'Data')->required()->date('d/m/Y')
            ->field('time', 'Hora')->required()->date('H:i')
            ->field('boards', 'Boards')->required()->isArray();

        if(! $validator->isValid()){
            return $this->response->invalidParams(
                'Falha ao criar nova Retro.',
                $validator->errorMessages
            );
        }

        $validated = $validator->validatedFields;

        foreach ($validated['boards'] as $board) {
            $validator = new ValidationHelper($board, $this->logger);
            $validator
                ->field('name', 'Nome')->required()->maxLen(100)
                ->field('color', 'Cor')->required()->alphaNum(['#'])->maxLen(100);

            if(! $validator->isValid()){
                return $this->response->invalidParams(
                    'Falha ao criar nova Retro.',
                    $validator->errorMessages
                );
            }
        }

        try {
            $result = $this->retroService->insertRetro($validated);
            return $this->response->success($result);
        } catch (\Throwable $th) {
            return $this->response->badRequest($th->getMessage());
            
        }        
    }
}
