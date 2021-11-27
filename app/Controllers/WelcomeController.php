<?php

namespace App\Controllers;

class WelcomeController
{
    public function __invoke(array $data)
    {
        return jsonResponse([
            'message' => 'This is the welcome page',
            'data' => $data
        ]);
    }
}
