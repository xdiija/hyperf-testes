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
use App\Repository\MunicipioRepository;

class MunicipioService
{
		private String $prefix = 'municipios';
    private RedisDriver $redis;
    private MunicipioRepository $municipioRepository;

    public function __construct(
       MunicipioRepository $municipioRepository,
			 RedisDriver $redis,
    ) {
        $this->municipioRepository = $municipioRepository;
				$this->redis = $redis;
    }

    public function getMunicipio(int $idMunicipio)
    {
				$municipio = $this->redis->hget($this->prefix, (String) $idMunicipio);
				if (!$municipio){
        	$result = $this->municipioRepository->getMunicipio($idMunicipio);
					if ($result != null) {
						$this->redis->hset($this->prefix, $result['id_municipio'], json_encode($result));
					}
					return $result != null ? $result : [];
				}
        return json_decode($municipio, true);
    }

		public function cacheMunicipios()
		{
			$municipios = $this->municipioRepository->getAllMunicipios();
			foreach($municipios as $municipio){
				$this->redis->hset($this->prefix, $municipio['id_municipio'], json_encode($municipio));
			}
		}
}
