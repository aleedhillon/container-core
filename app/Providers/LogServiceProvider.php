<?php

namespace App\Providers;

use App\Contracts\Logger;
use App\Contracts\ServiceProvider;
use App\Core\Container;
use App\Services\FileLoggingService;

class LogServiceProvider implements ServiceProvider
{
    public function register(Container $container): void
    {
        $container->bind(Logger::class, FileLoggingService::class);
    }
}
