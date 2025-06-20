<?php
declare(strict_types=1);

namespace App\Support;

use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Constant\ResponseCode;

class Response
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response) {
        $this->response = $response;
    }

    public function success(array $data = [], string $message = 'success')
    {
        return $this->response->json([
            'message' => $message,
            'data' => $data
        ])->withStatus(ResponseCode::OK);
    }

    public function badRequest(string $message = 'fail', array $data = [])
    {
        return $this->response->json([
            'message' => $message,
            'data' => $data
        ])->withStatus(ResponseCode::BAD_REQUEST);
    }

    public function invalidParams(string $message = 'fail', array $data = [])
    {
        return $this->response->json([
            'message' => $message,
            'data' => $data
        ])->withStatus(ResponseCode::UNPROCESSABLE_ENTITY);
    }

    public function serverError(string $message = 'fail', array $data = [])
    {
        return $this->response->json([
            'message' => $message,
            'data' => $data
        ])->withStatus(ResponseCode::SERVER_ERROR);
    }
}