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

use App\Helper\RedisDriver;
use App\Repository\PessoaRepository;

class PessoaService
{
    private PessoaRepository $pessoaRepository;

    public function __construct(
			PessoaRepository $pessoaRepository
    ) {

        $this->pessoaRepository = $pessoaRepository;
    }

    public function getPessoa(int $idPessoa)
    {
        $result = $this->pessoaRepository->getPessoa($idPessoa);
        return $result;
    }
}
