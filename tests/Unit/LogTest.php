<?php

namespace Unit;

use App\Services\Log;
use PHPUnit\Framework\MockObject\MockClass;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    public function test_log_service_is_logging()
    {
        $logServiceMock = $this->createMock(Log::class);

        $logServiceMock->method('info')->willReturn(true);

        $logDone = $logServiceMock->info('test message', [
            'name' => 'PHPUnit'
        ]);

        $this->assertTrue($logDone);
    }
}
