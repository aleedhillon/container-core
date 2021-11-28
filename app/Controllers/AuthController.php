<?php

namespace App\Controllers;

class AuthController
{
    protected $authEmail = 'aleedhillon@gmail.com';

    public function login(array $data)
    {
        $errors = [];

        if (!isset($data['email'])) {
            $errors['email'][] = 'email field is required.';
        }

        if (count($errors)) {
            return validationErrors($errors);
        }

        $email = $data['email'];

        if ($email !== $this->authEmail) {
            return jsonResponse([
                'message' => 'Email is not correct.'
            ], 401);
        }

        $_SESSION['email'] = $email;
        setcookie('username', 'aleedhillon', time() + 60);

        return jsonResponse([
            'message' => 'You are authenticated sucessfully',
            'email' => $email
        ]);
    }

    public function logout(array $data)
    {
        checkAuth();

        unset($_SESSION['email']);

        setcookie('username', '', time() - 99999);

        return jsonResponse([
            'message' => 'You have logged out successfully'
        ]);
    }
}
