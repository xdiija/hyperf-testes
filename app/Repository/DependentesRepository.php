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

namespace App\Repository;

use App\Helper\Database;

final class DependentesRepository
{
    private Database $db;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    public function getDependentes(int $idUsuarioApp)
    {
        $sql = "SELECT id_pessoa, id_usuario_app, ativo, atualizacao_dth
								FROM public.usuario_app_dependente
								WHERE id_usuario_app = {$idUsuarioApp}";
        $result = $this->db->run($sql);
        return ! empty($result) ? $result : null;
    }
}
