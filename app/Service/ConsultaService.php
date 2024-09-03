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
use App\Repository\ConsultaRepository;

class ConsultaService
{
    private RedisDriver $redis;
    private ConsultaRepository $consultaRepository;

    public function __construct(
       ConsultaRepository $consultaRepository
    ) {
        $this->consultaRepository = $consultaRepository;
    }

    public function getConsulta(int $idConsulta)
    {
        $result = $this->consultaRepository->getConsulta($idConsulta);
        return $result;
    }
}
