<?php

use App\Services\Application;
use App\Services\FileLoggingService;
use App\Services\Log;

require_once __DIR__ .'/../vendor/autoload.php';
require_once __DIR__.'/../routes/web.php';

$application = new Application($router);

$application->run($router);
