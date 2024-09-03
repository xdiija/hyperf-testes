<?php

declare(strict_types=1);

namespace App\Service;

use App\Helper\RedisDriver;
use App\Repository\UnidadeRepository;

class UnidadeService
{
		private String $prefix = 'unidades';
    private RedisDriver $redis;
    private UnidadeRepository $unidadeRepository;

    public function __construct(
			UnidadeRepository $unidadeRepository,
			RedisDriver $redis,
    )
		{
      $this->unidadeRepository = $unidadeRepository;
			$this->redis = $redis;
    }

    public function getUnidadeById(int $idUnidade)
    {
			$unidade = $this->redis->hget($this->prefix, (String) $idUnidade);
			if (!$unidade){
				$result = $this->unidadeRepository->getUnidadeById($idUnidade);
				if ($result != null) {
					$this->redis->hset($this->prefix, $result['id_unidade'], json_encode($result));
				}
				return $result != null ? $result : [];
			}
			return json_decode($unidade, true);
    }
}
