<?php

use App\Controllers\AuthController;
use App\Controllers\FileController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Services\Router;
use App\Controllers\WelcomeController;

require_once __DIR__ . '/../vendor/autoload.php';

loadEnv();

session_start([
    'name' => 'php_requests_session',
    'cookie_domain' => 'php-requests.test',
    'cookie_httponly' => true,
    'cookie_samesite' => true,
    'cookie_lifetime' => 120
]);

$router = new Router;

$router->get('/', WelcomeController::class);
$router->get('/posts', [PostController::class, 'index']);
$router->post('/posts', [PostController::class, 'store']);

$router->get('/files', [FileController::class, 'get']);
$router->post('/files', [FileController::class, 'store']);

$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/users', [UserController::class, 'index']);
$router->post('/users', [UserController::class, 'store']);

$path = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'])['path'] : '';
$method =  $_SERVER['REQUEST_METHOD'] ?? '';

try {
    $router->resolve($path, $method);
} catch (\Throwable $th) {
    exceptionToResponse($th);
}
