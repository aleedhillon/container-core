<?php

namespace App\Services;

use App\Contracts\Logger;

class Log
{
    protected $logger;

    public function __construct()
    {
        $this->logger = new FileLoggingService;
    }

    public function info(string $message, array $data = [])
    {
        return $this->logger->log($message, $data);
    }
}
