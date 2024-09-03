<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Service\TrendingService;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class IndexController extends AbstractController
{

    public function index(RequestInterface $request, HttpResponse $response): ResponseInterface
    {
        return $response->json(['alive' => true]);

    }

    public function user(RequestInterface $request)
    {
        return ["teste" => "index/user"];
    }
}
