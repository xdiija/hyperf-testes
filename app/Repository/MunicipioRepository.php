<?php

declare(strict_types=1);

namespace App\Repository;

use App\Helper\Database;
use Hyperf\Cache\Annotation\Cacheable;

use function PHPUnit\Framework\isNull;

final class MunicipioRepository
{
    private Database $db;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    #[Cacheable(prefix: "php-hyperf:municipio-getmunicipio", ttl: 900, listener: "municipio-get-municipio-by-id")]
    public function getMunicipio(int $idMunicipio)
    {
        $sql = "SELECT id_municipio, nome, ativo FROM public.municipio WHERE id_municipio = $idMunicipio";
        $result = $this->db->run($sql);
        return !empty($result) ? $result[0] : null;
    }

		public function getAllMunicipios()
		{
			$sql = "SELECT id_municipio, nome, ativo
							FROM public.municipio";
			$result = $this->db->run($sql);
			return !empty($result) ? $result : null;
		}
}
