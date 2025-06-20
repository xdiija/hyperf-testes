<?php

declare(strict_types=1);

namespace App\Repository;

final class UsuarioRepository extends Repository
{
    public function findByEmail(string $email): array
    {
        $sql = "SELECT
                    id,
                    google_id,
                    email,
                    name,
                    avatar_url,
                    is_active,
                    last_login_at,
                    created_at
                FROM public.usuarios
                WHERE email = '{$email}'";
        return $this->db->fetch($sql) ?? [];
    }
    public function findById(int $id): array
    {
        $sql = "SELECT
                    id,
                    google_id,
                    email,
                    name,
                    avatar_url,
                    is_active,
                    last_login_at,
                    created_at
                FROM public.usuarios
                WHERE id = {$id}";
        return $this->db->fetch($sql) ?? [];
    }

    public function create(array $data): array
    {
        $table = "public.usuarios";
        $values = [
            'google_id' => $data['google_id'],
            'email' => $data['email'],
            'name' => $data['name'],
            'avatar_url' => $data['avatar_url']
        ];
        return $this->db->insert($table, $values);
    }
}
