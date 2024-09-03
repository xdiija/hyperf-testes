<?php

declare(strict_types=1);

namespace App\Repository;

use App\Helper\Database;
use Hyperf\Cache\Annotation\Cacheable;

final class VacinaRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    #[Cacheable(prefix: "vacina-getvacina", ttl: 900, listener: "vacina-get-vacina-by-id")]
    public function getVacinas(int $idPessoa): array
    {   
        $sql = "SELECT
                    vacina_aprazamento.id,
                    vacina_aprazamento.nome_vacina,
                    vacina_aprazamento.data_aprazamento,
                    vacina_aprazamento.desativado
                FROM
                    vacina_aprazamento
                WHERE
                    id_pessoa = $idPessoa
                ORDER BY
                    data_aprazamento DESC";
        $result = $this->db->run($sql);
        return !empty($result) ? $result : [];
    }
}