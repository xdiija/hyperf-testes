<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Service\AgendaService;
use Hyperf\HttpServer\Contract\RequestInterface;

class AgendaController extends AbstractController
{
    private AgendaService $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
    }

    public function getAgenda(RequestInterface $request, int $matricula = 0)
    {
				$loggedInUser = $request->getAttribute('loggedInUser');
				$matricula = (int) json_decode($loggedInUser, true)['matricula'];
        if ($matricula == 0) {
            return 'Matricula do usuário inválida';
        }
				$requestValues = [
					'dataAgenda' => $request->header('dataAgenda', ''),
				];
        return $this->agendaService->getAgenda($matricula, $requestValues);
    }
}