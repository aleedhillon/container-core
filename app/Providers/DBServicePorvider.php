<?php

namespace App\Providers;

use App\Contracts\ServiceProvider;
use App\Core\Container;
use App\Services\DB;

class DBServicePorvider implements ServiceProvider
{
    public function register(Container $container): void
    {
        $container->singleton(DB::class, function() {
            return new DB;
        });
    }
}
