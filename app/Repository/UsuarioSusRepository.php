<?php

declare(strict_types=1);

namespace App\Repository;

use App\Helper\Database;
use Hyperf\Cache\Annotation\Cacheable;

final class UsuarioSusRepository
{
    private Database $db;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }
    public function getUsuarioSus(int $idUsuarioSus)
    {
        $sql = "SELECT id_municipio, id_pessoa, id, matricula, ativo, criacao_dth
                FROM public.usuario_sus
                WHERE id = $idUsuarioSus";
        $result = $this->db->run($sql);
        return !empty($result) ? $result[0] : null;
    }
}
