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

use App\Service\PessoaService;
use Hyperf\HttpServer\Contract\RequestInterface;

class PessoaController extends AbstractController
{
    private PessoaService $pessoaService;

    public function __construct(PessoaService $pessoaService)
    {
       $this->pessoaService = $pessoaService;
    }

    public function getPessoa(RequestInterface $request)
    {
        $idPessoa = (int) $request->query("idpessoa", 0);
        if($idPessoa == 0){
            return "Id Pessoa inválido";
        }
        $result = $this->pessoaService->getPessoa($idPessoa);
        return $result;
    }

}
