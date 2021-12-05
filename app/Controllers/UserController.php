<?php

namespace App\Controllers;

use App\Services\Collection;
use App\Services\DB;
use App\Services\Request;
use PDO;

class UserController
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function index()
    {
        $query = 'SELECT * FROM users ORDER BY created_at';

        $users = collect($this->db->RawQuery($query));

        $users = $users->map(function ($user) {
            return [
                'name' => $user->name
            ];
        });

        return response()->json([
            'posts' => $users
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

    public function store(Request $request)
    {
        $data = $request->validate(['name', 'email']);

        $connection = $this->db->getConnection();

        $emailExists = $connection->query("SELECT EXISTS (SELECT * FROM users where email = '{$data['email']}') as has")
            ->fetch()['has'];

        if($emailExists) {
            return validationErrors([
                'email' => 'Email already exists' 
            ]);
        }

        $query = 'INSERT INTO users(name, email, is_active) values(:name, :email, :is_active)';

        $statement = $connection->prepare($query);

        $statement->bindValue('name', $data['name']);
        $statement->bindValue('email', $data['email']);
        $statement->bindValue('is_active', false, PDO::PARAM_BOOL);

        $statement->execute();

        $userId = $connection->lastInsertId();

        $user = $connection->query("SELECT * FROM users where id = {$userId} LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        return response()->json([
            'data' => $user
        ]);
    }
}
