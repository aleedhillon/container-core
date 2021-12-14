<?php

namespace App\Providers;

use App\Contracts\ServiceProvider;
use App\Core\Container;
use App\Services\Request;
use App\Services\Router;

class RouteServiceProvider implements ServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(Router::class, function(Container $container) {
            return new Router($container->get(Request::class));
        });

        require_once __DIR__ . '/../../routes/web.php';
    }
}
