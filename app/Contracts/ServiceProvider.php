<?php

namespace App\Contracts;

use App\Core\Container;

interface ServiceProvider
{
    public function register(Container $container): void;
}
