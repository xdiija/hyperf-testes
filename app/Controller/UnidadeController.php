<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\UnidadeService;
use Hyperf\HttpServer\Contract\RequestInterface;

class UnidadeController extends AbstractController
{
    private UnidadeService $unidadeService;

    public function __construct(UnidadeService $unidadeService)
    {
       $this->unidadeService = $unidadeService;
    }

    public function getUnidadeById(RequestInterface $request)
    {
        $idUnidade = (int) $request->query("idunidade", 0);
        if($idUnidade == 0){
            return "Id Unidade inválido";
        }
        $unidade = $this->unidadeService->getUnidadeById($idUnidade);
        return $unidade;
    }
}
