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

namespace App\Service;

use App\Repository\UsuarioSusRepository;
use App\Service\PessoaService;
use App\Service\MunicipioService;
use Hyperf\Coroutine\Parallel;

class UsuarioSusService
{
    private UsuarioSusRepository $usuarioSusRepository;
		private PessoaService $pessoaService;
		private MunicipioService $municipioService;

    public function __construct(
        UsuarioSusRepository $usuarioSusRepository,
        PessoaService $pessoaService,
        MunicipioService $municipioService,
    ) {
        $this->usuarioSusRepository = $usuarioSusRepository;
        $this->pessoaService = $pessoaService;
        $this->municipioService = $municipioService;
    }

    public function getUsuarioSus(array $requestValues = [], int $idUsuarioSus = 0)
    {
        $usuarioSus = $this->usuarioSusRepository->getUsuarioSus($idUsuarioSus);
        $parallel = new Parallel();
		$parallel->add(function() use($usuarioSus){
        	return $this->pessoaService->getPessoa($usuarioSus['id_pessoa']);
        }, 'pessoa');
        $parallel->add(function() use($usuarioSus){
			return $this->municipioService->getMunicipio($usuarioSus['id_municipio']);
        }, 'municipio');
		$dados = $parallel->wait();
		$result['usuarioSus'] = $usuarioSus;
		$result['pessoa'] = $requestValues['mostrarPessoa'] == '1' ?  $dados['pessoa'] : [];
		$result['municipio'] = $requestValues['mostrarMunicipio'] == '1' ?  $dados['municipio'] : [];
      	return $result;
    }
}
