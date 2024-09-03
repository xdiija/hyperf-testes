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

use App\Service\MunicipioService;
use Hyperf\HttpServer\Contract\RequestInterface;

class MunicipioController extends AbstractController
{
    private MunicipioService $municipioService;

    public function __construct(MunicipioService $municipioService)
    {
       $this->municipioService = $municipioService;
    }

    public function getMunicipio(RequestInterface $request)
    {
        $idMunicipio = (int) $request->query("idmunicipio", 0);
        if($idMunicipio == 0){
            return "Id Municipio inválido";
        }
        $result = $this->municipioService->getMunicipio($idMunicipio);
        return $result;
    }

}
