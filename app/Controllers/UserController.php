<?php

namespace App\Controllers;

use PDO;
use App\Models\User;
use App\Services\DB;
use App\Services\Request;
use App\Services\Collection;
use App\Services\Application;

class UserController
{
    protected User $user;
    protected Request $request;
    protected DB $db;

    public function __construct(Request $request, User $user, DB $db)
    {
        $this->user = $user;
        $this->request = $request;
        $this->db = $db;
    }

    public function index()
    {
        $users = $this->user->get();

        return response()->json([
            'data' => $users
        ]);
    }

    public function show()
    {
        $connection = $this->db->getConnection();

        $query = 'SELECT * FROM users where id = 1 LIMIT 1';

        $query = $connection->query($query);

        return response()->json([
            'data' => $query->fetch(PDO::FETCH_ASSOC)
        ]);
    }

    public function store()
    {
        $data = $this->request->validate(['name', 'email']);

        if ($this->user->whereExists('email', $data['email'])) {
            return validationErrors([
                'email' => 'Email already exists'
            ]);
        }

        $user = $this->user->create($data);

        return response()->json([
            'message' => 'User has been created successfully',
            'data' => $user
        ]);
    }
}
