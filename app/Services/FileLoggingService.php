<?php

namespace App\Services;

use App\Contracts\Logger;

class FileLoggingService implements Logger
{
    public function log(string $message, array $data): bool
    {
        $logPath = getLogPath('application');

        $content = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL . json_encode($data) . PHP_EOL;

        return file_put_contents($logPath, $content, FILE_APPEND);
    }
}
