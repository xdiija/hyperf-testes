<?php

declare(strict_types=1);

namespace App\Repository;

use App\Helper\RawExpression;

final class RetroRepository extends Repository
{
    public function getAll(): array
    {
        $sql = "SELECT
                    id,
                    squad_id,
                    title,
                    slug,
                    date_time,
                    created_at,
                    updated_at
                FROM public.retros
                ORDER BY created_at DESC";
        return $this->db->query($sql) ?? [];
    }

    public function insertRetro(array $data): array
    {
        $table = "public.retros";
        $values = [
            'squad_id' => $data['squad_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'date_time' => $data['date_time'],
            'created_at' => new RawExpression('NOW()'),
            'updated_at' => new RawExpression('NOW()')
        ];
        return $this->db->insert($table, $values);
    }

    public function insertBoard(array $data, int $retroId): array
    {
        $table = "public.boards";
        $values = [
            'retro_id' => $retroId,
            'name' => $data['name'],
            'color' => $data['color'],
            'created_at' => 'NOW()'
        ];
        return $this->db->insert($table, $values);
    }

    public function insertComment(array $data): array
    {
        $table = "public.comments";
        $values = [
            'board_id' => $data['board_id'],
            'user_id' => $data['user_id'],
            'content' => $data['content'],
            'created_at' => 'NOW()'
        ];
        return $this->db->insert($table, $values);
    }

    public function getCommentById(int $id): array
    {
        $sql = "SELECT
                    id,
                    content,
                    user_id,
                    created_at,
                    updated_at
                FROM public.comments
                WHERE id = $id";
        return $this->db->fetch($sql) ?? [];
    }

    public function getBoardsByRetroId(int $retroId): array
    {
        $sql = "SELECT
                    boards.id AS board_id,
                    boards.name AS board_name,
                    boards.color AS board_color
                FROM public.boards
                WHERE retro_id = $retroId
                ORDER BY id";
        return $this->db->query($sql) ?? [];
    }

    public function getCommentsByBoardId(int $boardId): array
    {
        $sql = "SELECT
                    comments.id AS comment_id,
                    comments.content AS comment_content,
                    comments.user_id
                FROM public.comments
                WHERE comments.board_id = $boardId
                AND deleted_at IS NULL
                ORDER BY id";
        return $this->db->query($sql) ?? [];
    }

    public function getRetroIdBySlug(string $slug): ?int
    {
        $sql = "SELECT
                    id
                FROM public.retros
                WHERE slug = '$slug'";
        return $this->db->fetch($sql)['id'] ?? [];
    }

    public function getRetroBySlug(string $slug): array
    {
        $sql = "SELECT
                    *
                FROM public.retros
                WHERE slug = '$slug'";
        return $this->db->fetch($sql) ?? [];
    }

    public function deleteComment(int $userId, int $commentId): int
    {
        $sql = "UPDATE
                    comments
                SET
                    deleted_by = $userId,
                    deleted_at = NOW()
                WHERE id = $commentId";
        return $this->db->execute($sql) ?? [];
    }

    public function editComment(string $content, int $commentId): int
    {
        $sql = "UPDATE
                    comments
                SET
                    content = '{$content}',
                    updated_at = NOW()
                WHERE id = $commentId";
        return $this->db->execute($sql) ?? [];
    }
}
