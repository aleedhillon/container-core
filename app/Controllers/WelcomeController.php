<?php

namespace App\Controllers;

use App\Services\Log;
use App\Services\Request;
use App\Services\View;

class WelcomeController
{
    protected Log $log;

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function __invoke(Request $request)
    {
        $this->log->info('Incoming request on welcome page');
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'This is the welcome page.'
            ]);
        }

        return view('welcome');
    }
}
