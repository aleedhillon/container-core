<?php

namespace App\Controllers;

class WelcomeController
{
    public function __invoke(array $data)
    {
        $_SESSION['count'] = ($_SESSION['count'] ?? 0) + 1;

        $username = $_COOKIE['username'] ?? null;
        return jsonResponse([
            'message' => 'This is the welcome page',
            'data' => $data,
            'count' => $_SESSION['count'],
            'username' => $username
        ]);
    }
}
