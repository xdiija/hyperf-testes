<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\User\User;
use App\Model\User\UserInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Helper\Database;
use Hyperf\Cache\Annotation\Cacheable;

final class UserRepository implements UserRepositoryInterface
{
    private Database $db;

    private ?UserInterface $loggedInUser = null;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    public function setLoggedInUser(UserInterface $loggedInUser){
        $this->loggedInUser = $loggedInUser;
    }

    public function getUser(int $userId): User
    {
        return new User();
    }

    #[Cacheable(prefix: "users-findonebytoken", ttl: 900, listener: "find-one-by-token")]
    public function findOnebyToken(string $api_token){

        $sql = "select u.id, u.email, u.username, u.avatar, u.roles, u.bio, u.created_at, u.updated_at from users.users u where u.api_token = '" . $api_token . "'";

        $result = $this->db->run($sql);

        return !empty($result)? $result : null;
    }

    #[Cacheable(prefix: "users-usersettings", ttl: 900, listener: "get-user-settings")]
    public function getUserSettings(int $userId){

        $sql = "select id , key, value, visibility from users.settings where user_id = $userId and deleted_at is null";
        $result = $this->db->run($sql);
        return !empty($result)? $result : null;
    }

    #[Cacheable(prefix: "users-findonebyidusername", ttl: 900, listener: "find-one-by-username")]
    public function findOneByIdUsername(int $id, string $username){

        $sql = "select u.id, u.email, u.username, u.avatar, u.roles, u.bio, u.created_at from users.users u where u.id = " . $id ;
        $result = $this->db->run($sql);
        return !empty($result)? $result : null;
    }

}
