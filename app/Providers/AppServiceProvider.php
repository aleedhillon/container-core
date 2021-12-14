<?php

namespace App\Providers;

use App\Contracts\ServiceProvider;
use App\Core\Container;
use App\Services\Request;
use App\Services\Router;

class AppServiceProvider implements ServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(Request::class, function ($container) {
            return new Request;
        });
    }
}
