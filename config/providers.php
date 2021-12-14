<?php

use App\Providers\AppServiceProvider;
use App\Providers\DBServicePorvider;
use App\Providers\LogServiceProvider;
use App\Providers\RouteServiceProvider;

return [
    AppServiceProvider::class,
    LogServiceProvider::class,
    RouteServiceProvider::class,
    DBServicePorvider::class
];