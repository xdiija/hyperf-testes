<?php

declare(strict_types=1);

namespace App\Repository;

use App\Helper\Database;
use Hyperf\Cache\Annotation\Cacheable;

final class ConsultaRepository
{
    private Database $db;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

		#[Cacheable(prefix: "agenda-getconsulta", ttl: 900, listener: "agenda-get-consulta-by-id")]
    public function getConsulta(int $idConsulta)
    {
        $sql = "SELECT * FROM public.agenda LIMIT 10000";
        $result = $this->db->run($sql);
        return !empty($result) ? $result : null;
    }
}
