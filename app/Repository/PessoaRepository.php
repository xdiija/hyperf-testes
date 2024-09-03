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
use Hyperf\Cache\Annotation\Cacheable;

final class PessoaRepository
{
    private Database $db;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    #[Cacheable(prefix: 'php-hyperf:pessoa-getpessoa', ttl: 900, listener: 'pessoa-get-pessoa-by-id')]
    public function getPessoa(int $idPessoa)
    {
        $sql = "SELECT nome, cpf, registro_dth, telefone, email, endereco, nome_social, atualizacao_dth FROM public.pessoa WHERE id =  {$idPessoa}";
        $result = $this->db->run($sql);
        return ! empty($result) ? $result[0] : null;
    }
}
