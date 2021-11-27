<?php

use App\Controllers\PostController;
use App\Services\Router;
use App\Controllers\WelcomeController;
use App\Services\DB;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router;

$router->get('/', WelcomeController::class);
$router->get('/posts', [PostController::class, 'index']);
$router->post('/posts', [PostController::class, 'store']);

$path = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'])['path'] : '';
$method =  $_SERVER['REQUEST_METHOD'] ?? '`';

$router->resolve($path, $method);
