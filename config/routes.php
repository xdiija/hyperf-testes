<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use App\Middleware\CorsMiddleware;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/live', 'App\Controller\IndexController@index');
Router::addRoute(['POST'], '/login', 'App\Controller\UsuarioController@login');
Router::addRoute(['GET'], '/consulta', 'App\Controller\ConsultaController@getConsulta', ['middleware' => [AuthMiddleware::class, CorsMiddleware::class]]);

Router::addGroup('/auth', function () {
    Router::get('/google', 'App\Controller\AuthController@redirectToGoogle');
    Router::get('/google/callback', 'App\Controller\AuthController@handleGoogleCallback');
    Router::get('/callback', 'App\Controller\AuthController@callbackPage');
    Router::post('/validate-token', 'App\Controller\AuthController@validateToken');
}, ['middleware' => [CorsMiddleware::class]]);


Router::addGroup('/retro', function () {
    Router::get('/list', 'App\Controller\RetroController@index');
    Router::get('/{slug}', 'App\Controller\RetroController@show');
    Router::post('/create', 'App\Controller\RetroController@store');
    Router::get("/join/{slug}", 'App\Controller\RetroController@show');
}, ['middleware' => [CorsMiddleware::class]]);

Router::addServer('ws', function () {
    Router::get('/retro', 'App\Controller\RetroWebSocketController');
});

Router::get('/favicon.ico', function () {
    return '';
});
