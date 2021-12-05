<?php

use App\Services\Router;
use App\Controllers\AuthController;
use App\Controllers\FileController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\WelcomeController;

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
