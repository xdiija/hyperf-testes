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

use App\Service\UsuarioSusService;
use Hyperf\HttpServer\Contract\RequestInterface;

class UsuarioSusController extends AbstractController
{
    private UsuarioSusService $usuarioSusService;

    public function __construct(UsuarioSusService $usuarioSusService)
    {
       $this->usuarioSusService = $usuarioSusService;
    }

    public function getUsuarioSus(RequestInterface $request, int $idUsuarioSus)
    {   
        $loggedInUser = $request->getAttribute('loggedInUser');
        if(json_decode($loggedInUser, true)['idUsuarioSus'] != $idUsuarioSus){
            return [
                'code' => 'UNAUTHORIZED_ACCESS',
                'message' => 'Não é possível acessar dados de outro usuário!'
            ];
        }
        if($idUsuarioSus == 0){
            return "Id Usuário Sus inválido";
        }
        $requestValues = [
            'mostrarMunicipio' => $request->header('mostrarMunicipio', '1'),
            'mostrarPessoa' => $request->header('mostrarPessoa', '1')
        ];
        $result = $this->usuarioSusService->getUsuarioSus($requestValues, $idUsuarioSus);
        return $result;
    }
}
