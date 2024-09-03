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

use App\Service\ConsultaService;
use Hyperf\HttpServer\Contract\RequestInterface;

class ConsultaController extends AbstractController
{
    private ConsultaService $consultaService;

    public function __construct(ConsultaService $consultaService)
    {
       $this->consultaService = $consultaService;
    }

    public function getConsulta(RequestInterface $request)
    {
        $idConsulta = (int) $request->query("idconsulta", 0);
        if($idConsulta == 0){
            return "Id Consulta inválido";
        }
        $result = $this->consultaService->getConsulta($idConsulta);
        return $result;
    }

}
