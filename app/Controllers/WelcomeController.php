<?php

namespace App\Controllers;

use App\Services\Request;
use App\Services\View;

class WelcomeController
{
    public function __invoke(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'This is the welcome page.'
            ]);
        }

        return view('welcome');
    }
}
