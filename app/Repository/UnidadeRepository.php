<?php

declare(strict_types=1);

namespace App\Repository;

use App\Helper\Database;
use Hyperf\Cache\Annotation\Cacheable;

final class UnidadeRepository
{
    private Database $db;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    #[Cacheable(prefix: "php-hyperf:unidade-getunidadebyid", ttl: 900, listener: "unidade-get-unidade-by-id")]
    public function getUnidadeById(int $idUnidade)
    {
        $sql = "SELECT
									id_municipio, id_unidade, nome, endereco, numero, cep, bairro, latitude, longitude, visivel, complemento,
									telefone, criacao_dth, atualizacao_dth, desativado
								FROM public.unidade
								WHERE id_unidade = $idUnidade";
        $result = $this->db->run($sql);
        return !empty($result) ? $result[0] : null;
    }
}
