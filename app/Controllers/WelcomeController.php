<?php

namespace App\Controllers;

use App\Services\Log;
use App\Services\Request;
use App\Services\View;

class WelcomeController
{
    protected Log $log;
    protected Request $request;

    public function __construct(Log $log, Request $request)
    {
        $this->log = $log;
        $this->request = $request;
    }

    public function __invoke()
    {
        $this->log->info('Incoming request on welcome page');
        if ($this->request->wantsJson()) {
            return response()->json([
                'message' => 'This is the welcome page.'
            ]);
        }

        return view('welcome');
    }
}
