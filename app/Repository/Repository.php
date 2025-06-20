<?php

declare(strict_types=1);

namespace App\Repository;

use App\Helper\Database;

abstract class Repository
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
}
