<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProtectedMiddleware implements MiddlewareInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    private $secretToken = 'aperto de mao secreto do Gustavo';

    /**
     * @var HttpResponse
     */
    protected $response;
    /**
     * @var JWT
     */
    protected $jwt;

    public function __construct(
        HttpResponse $response,
        RequestInterface $request,
    )
    {
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = [
            'code' => 'NOT_ADMIN_CREDENTIALS',
            'message' => 'Not admin credentials'
        ];

        $apitoken = $request->getHeaderLine('x-api-key');

        if (!empty($apitoken) && $apitoken == $this->secretToken) {
            return $handler->handle($request);
        } else {
            return $this->response->json($data);
        }
    }
}