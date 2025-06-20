<?php
declare(strict_types=1);

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Hyperf\HttpServer\Contract\RequestInterface;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onMessage($server, $frame): void
    {
        // Type hinting removed to match interface
        // You can add type checking inside the method if needed
        if (!$server instanceof Server || !$frame instanceof Frame) {
            return;
        }

        // Broadcast message to all connected clients
        foreach ($server->connections as $fd) {
            if ($server->isEstablished($fd)) {
                $server->push($fd, json_encode([
                    'user' => 'User ' . $frame->fd,
                    'message' => $frame->data,
                    'time' => date('H:i:s')
                ]));
            }
        }
    }

    public function onClose($server, $fd, $reactorId): void
    {
        if (!$server instanceof Server) {
            return;
        }

        // Notify all clients that a user left
        foreach ($server->connections as $clientFd) {
            if ($server->isEstablished($clientFd)) {
                $server->push($clientFd, json_encode([
                    'system' => true,
                    'message' => "User {$fd} has left the chat",
                ]));
            }
        }
    }

    public function onOpen($server, $request): void
    {
        if (!$server instanceof Server || !$request instanceof Request) {
            return;
        }
        foreach ($server->connections as $fd) {
            if ($server->isEstablished($fd)) {
                $server->push($fd, json_encode([
                    'system' => true,
                    'message' => "User {$request->fd} has joined the chat",
                ]));
            }
        }
    }
}