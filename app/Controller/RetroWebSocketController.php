<?php
declare(strict_types=1);

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\WebSocketServer\Context;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use App\Service\RetroService;
use App\Service\UsuarioService;
use Hyperf\Contract\StdoutLoggerInterface;

class RetroWebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    private RetroService $retroService;
    private UsuarioService $usuarioService;
    private StdoutLoggerInterface $logger;
    
    public function __construct(
        RetroService $retroService,
        StdoutLoggerInterface $logger,
        UsuarioService $usuarioService
    ) {
        $this->retroService = $retroService;
        $this->usuarioService = $usuarioService;
        $this->logger = $logger;
    }

    public function onMessage($server, $frame): void
    {
        $this->logger->info("[RetroWebSocketController]- onMessage ");
        if (!$server instanceof Server || !$frame instanceof Frame) {
            return;
        }

        $data = json_decode($frame->data, true);
        $retroSlug = Context::get('retro_slug');
        $userId = Context::get('user_id');

        if (empty($retroSlug) || empty($userId)) {
            return;
        }

        switch ($data['type'] ?? 'message') {
            case 'message':

                $commentData = [
                    'retro_id' => $this->retroService->getRetroIdBySlug($retroSlug),
                    'user_id' => $userId,
                    'content' => $data['content'],
                    'board_id' => $data['board_id'],
                ];
                $comment = $this->retroService->insertComment($commentData);
                
                $this->broadcastToRetro($server, $retroSlug, [
                    'type' => 'message',
                    'comment_id' => $comment['id'],
                    'user_id' => $userId,
                    'comment_content' => $data['content'],
                    'board_id' => $data['board_id'],
                    'timestamp' => time()
                ]);
                break;

            case 'remove_comment':
      
                $this->retroService->removeComment(
                    (int) $data['user_id'], (int) $data['comment_id']
                );
                
                $this->broadcastToRetro($server, $retroSlug, [
                    'type' => 'remove_comment',
                    'comment_id' => $data['comment_id'],
                    'timestamp' => time()
                ]);
                break;  
            
            case 'edit_comment':
      
                $result = $this->retroService->editComment($data);
                
                if($result){
                    $this->broadcastToRetro($server, $retroSlug, [
                        'type' => 'edit_comment',
                        'comment_id' => $data['comment_id'],
                        'content' => $data['content'],
                        'timestamp' => time()
                    ]);
                }
                break;  
        }
    }

    public function onOpen($server, $request): void
    {
        $this->logger->info("[RetroWebSocketController]- onOpen ");
        if (!$server instanceof Server || !$request instanceof Request) {
            return;
        }

        $retroSlug = $request->get['retro_slug'] ?? '';
        $userId = $request->get['user_id'] ?? 0;

        if (empty($retroSlug) || empty($userId)) {
            $server->close($request->fd);
            return;
        }

        var_dump($server->connection_list());

        $retro = $this->retroService->getRetroBySlug($retroSlug);

        if (empty($retro)) {
            $server->close($request->fd);
            return;
        }

        Context::set('retro_slug', $retroSlug);
        Context::set('user_id', $userId);

        $usuario = $this->usuarioService->getById((int) $userId);
        $this->removeUsersOldConnections($retroSlug, $request->fd, (int) $usuario['id'], $server);
        $this->retroService->addConnectionToRetro($retroSlug, $request->fd, $usuario);

        if ($server->isEstablished((int) $request->fd)) {

            $this->broadcastToRetro($server, $retroSlug, [
                'type' => 'user_joined',
                'user' => $usuario,
                'online_users' => $this->getOnlineUsers($retroSlug),
                'timestamp' => time()
            ]);

            $initialData = [
                'type' => 'init',
                'retro' => $retro,
                'user_id' => $userId,
                'timestamp' => time()
            ];

            $server->push((int) $request->fd, json_encode($initialData));
        }
    }

    public function onClose($server, $fd, $reactorId): void
    {
        $this->logger->info("[RetroWebSocketController]- onClose ");
        if (!$server instanceof Server) {
            return;
        }

        $retroSlug = Context::get('retro_slug');
        $userId = Context::get('user_id');
        $usuario = $this->usuarioService->getById((int) $userId);
        $this->retroService->removeConnectionFromRetro($retroSlug, $fd);

        if ($retroSlug && $userId) {
            $this->broadcastToRetro($server, $retroSlug, [
                'type' => 'user_left',
                'user' => $usuario,
                'online_users' => $this->getOnlineUsers($retroSlug),
                'timestamp' => time()
            ]);
        }

    }

    private function broadcastToRetro(Server $server, string $retroSlug, array $message): void
    {
        $connections = $this->retroService->getRetroConnections($retroSlug);       

        foreach ($connections as $fd => $value) {
            if ($server->isEstablished((int) $fd)) {
                $server->push((int) $fd, json_encode($message));
            }
        }
    }

    private function getOnlineUsers(string $retroSlug): array
    {
        $connections = $this->retroService->getRetroConnections($retroSlug);       
        $users = [];
        foreach ($connections as $fd => $value) {
            $users[] = $value;
        }
        return $users;
    }

    public function removeUsersOldConnections(string $retroSlug, int $fd,  int $userId, $server): void
    {
        $existingConnections = $this->retroService->getRetroConnections($retroSlug);
        foreach ($existingConnections as $existingFd => $existingUser) {
            if ($existingUser['id'] == $userId) {
                $this->retroService->removeConnectionFromRetro($retroSlug, $existingFd);

                if($fd != $existingFd && $server->isEstablished((int) $existingFd)){

                    $server->push((int)$existingFd, json_encode([
                        'type' => 'another_session_connected',
                        'message' => 'You have connected in another session or tab.',
                        'timestamp' => time(),
                    ]));
                    
                    $server->close((int) $existingFd);
                }
            }

        }
    }
}