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

use App\Repository\DependentesRepository;

class DependentesService
{
    private DependentesRepository $dependentesRepository;

    private PessoaService $pessoaService;

    public function __construct(
        DependentesRepository $dependentesRepository,
        PessoaService $pessoaService,
    ) {
        $this->dependentesRepository = $dependentesRepository;
        $this->pessoaService = $pessoaService;
    }

    public function getDependentes(int $idUsuarioApp)
    {
        $pessoa = $this->pessoaService->getPessoa(39);
        $dependentes = $this->dependentesRepository->getDependentes($idUsuarioApp);
        $rDependentes = [];
        foreach ($dependentes as $dependente) {
            $rDependente = $dependente;
            $rDependente['pessoa'] = $this->pessoaService->getPessoa($dependente['id_pessoa']);
            array_push($rDependentes, $rDependente);
        }
        $pessoa['dependentes'] = $rDependentes;

        return $pessoa;
    }
}
