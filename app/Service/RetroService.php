<?php

declare(strict_types=1);

namespace App\Service;

use App\Helper\RedisDriver;
use App\Repository\RetroRepository;
use Carbon\Carbon;
use Hyperf\Contract\StdoutLoggerInterface;

class RetroService
{
    private StdoutLoggerInterface $logger;
    private RetroRepository $retroRepository;
    private RedisDriver $redis;
    private string $redisPrefix = 'RetroService:';
    private string $logPrefix = '[RetroService]';
    private array $squads = [
        1 => 'APPocalipse',
        2 => 'CapsLoki',
        3 => 'TylenOlos',
        4 => 'CodeOfDuty'
    ];

    public function __construct(
        RetroRepository $retroRepository,
        RedisDriver $redis,
        StdoutLoggerInterface $logger,
    ) {
        $this->retroRepository = $retroRepository;
        $this->redis = $redis;
        $this->logger = $logger;
    }

    public function getAll(): array
    {   
        $result = $this->retroRepository->getAll();
        
        foreach ($result as &$retro) {
            $retro['squad_name'] = $this->squads[$retro['squad_id']];
            $retro['date_time'] = Carbon::parse($retro['date_time'])->format('d/m/Y H:i');
        }

        unset($retro);

        return $result;
    }

    public function insertRetro(array $data): array
    {   
        $data['slug'] = $this->generateRandomToken();
        
        $dateTimeBR = "{$data['date']} {$data['time']}";

        $data['date_time'] = Carbon::createFromFormat('d/m/Y H:i', $dateTimeBR)->format('Y-m-d H:i:s');
        $retro = $this->retroRepository->insertRetro($data);

        foreach ($data['boards'] as $board) {
            $this->retroRepository->insertBoard($board, $retro['id']);
        }

        return $this->getRetroBySlug($retro['slug']);
    }

    public function getRetroBySlug(string $retroSlug): array
    {   
        $retro = $this->retroRepository->getRetroBySlug($retroSlug);
        $retro['boards'] = $this->getBoardsAndComments($retro['id']);
        $retro['squad_name'] = $this->squads[$retro['squad_id']];
        $retro['date_time'] = Carbon::parse($retro['date_time'])->format('d/m/Y H:i');

        return $retro;
    }

    public function insertComment(array $data): array
    {   
        return $this->retroRepository->insertComment($data);
    }

    public function removeComment(int $userId, int $commentId): bool
    {   
        $comment = $this->retroRepository->getCommentById($commentId);

        //todo tratar retornos
        if(empty($comment) || $comment['user_id'] != $userId){
            return false;
        }

        return (bool) $this->retroRepository->deleteComment($userId, $commentId);
    }

    public function editComment(array $data): bool
    {   
        $commentId = (int) $data['comment_id'];
        $content = $data['content'] ?? '';
        $comment = $this->retroRepository->getCommentById($commentId);

        //todo tratar retornos
        if(empty($comment) || empty(trim($content))){
            return false;
        }

        if($comment['user_id'] == (int) $data['user_id']){
            return (bool) $this->retroRepository->editComment(
                $data['content'], $commentId
            );
        } else {
        //todo tratar retornos

            return false;
        }
    }

    private function getBoardsAndComments(int $retroId): array
    {   
        $boards = $this->retroRepository->getBoardsByRetroId($retroId);

        foreach ($boards as &$board) {
            $boardId = $board['board_id'];
            $comments = $this->retroRepository->getCommentsByBoardId($boardId);
            $board['comments'] = $comments;
        }

        return $boards;
    }

    public function getRetroIdBySlug(string $slug): ?int
    {
        return $this->retroRepository->getRetroIdBySlug($slug);
    }

    public function generateRandomToken($length = 12): string {
        return bin2hex(random_bytes($length / 2));
    }

    public function addConnectionToRetro(string $retroSlug, int $fd, array $user): void
    {
        $key = "{$this->redisPrefix}connections:{$retroSlug}";
        $userJson = json_encode($user);
        $this->redis->hSet($key, $fd, $userJson);
        $this->logger->info("{$this->logPrefix}.addConnectionToRetro - OK");
    }

    public function removeConnectionFromRetro(string $retroSlug, int $fd): void
    {
        $key = "{$this->redisPrefix}connections:{$retroSlug}";
        $this->redis->hDel($key, $fd);
        $this->logger->info("{$this->logPrefix}.removeConnectionFromRetro - OK");
    }

    public function getRetroConnections(string $retroSlug): array
    {
        $this->logger->info("{$this->logPrefix}.getRetroConnections - OK");
        $key = "{$this->redisPrefix}connections:{$retroSlug}";
        $connections = $this->redis->hGetAll($key) ?: [];
        
        $result = [];
        foreach ($connections as $fd => $userJson) {
            $result[(int)$fd] = json_decode($userJson, true);
        }
        
        return $result;
    }

}
