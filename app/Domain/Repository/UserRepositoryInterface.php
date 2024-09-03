<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Model\User\User;

interface UserRepositoryInterface
{
    public function getUser(int $userId): User;
}