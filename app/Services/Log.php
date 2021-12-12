<?php

namespace App\Services;

use App\Contracts\Logger;

class Log
{
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function info(string $message, array $data = [])
    {
        return $this->logger->log($message, $data);
    }
}
