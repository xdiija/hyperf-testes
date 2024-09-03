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
use App\Middleware\AuthMiddleware;
use App\Middleware\ProtectedMiddleware;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/live', 'App\Controller\IndexController@index');
Router::addRoute(['POST'], '/login', 'App\Controller\UsuarioAppController@login');
Router::addRoute(['GET'], '/consulta', 'App\Controller\ConsultaController@getConsulta', ['middleware' => [AuthMiddleware::class, ProtectedMiddleware::class]]);
Router::addRoute(['GET'], '/usuariosus/{idUsuarioSus}', 'App\Controller\UsuarioSusController@getUsuarioSus', ['middleware' => [AuthMiddleware::class]]);
Router::addRoute(['POST'], '/usuarioapp', 'App\Controller\UsuarioAppController@returnUsuarioByCredentials', ['middleware' => [AuthMiddleware::class]]);
Router::addRoute(['GET'], '/pessoa', 'App\Controller\PessoaController@getPessoa', ['middleware' => [AuthMiddleware::class, ProtectedMiddleware::class]]);
Router::addRoute(['GET'], '/municipio', 'App\Controller\MunicipioController@getMunicipio', ['middleware' => [ProtectedMiddleware::class]]);
Router::addRoute(['GET'], '/dependentes/{idUsuarioApp}', 'App\Controller\DependentesController@getDependentes', ['middleware' => [AuthMiddleware::class]]);
Router::addRoute(['GET'], '/agenda/{idUsuarioSus}', 'App\Controller\AgendaController@getAgenda', ['middleware' => [AuthMiddleware::class]]);
Router::addRoute(['GET'], '/unidade', 'App\Controller\UnidadeController@getUnidadeById', ['middleware' => [ProtectedMiddleware::class]]);
Router::addRoute(['GET'], '/vacinas/{idPessoa}', 'App\Controller\VacinaController@getVacinas', ['middleware' => [AuthMiddleware::class]]);

Router::get('/favicon.ico', function () {
    return '';
});
