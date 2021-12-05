<?php

namespace App\Controllers;

use App\Services\Collection;
use App\Services\DB;
use App\Services\Request;
use Exception;
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
        $query = 'SELECT * FROM users';

        $users = collect($this->db->RawQuery($query));

        return response()->json([
            'data' => $users->toArray()
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

        $connection->beginTransaction();

        try {
            $emailExists = $connection->query("SELECT EXISTS (SELECT * FROM users where email = '{$data['email']}') as has")
                ->fetch()['has'];

            if ($emailExists) {
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

            $connection->commit();
        } catch (\Throwable $th) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            throw $th;
        }

        return response()->json([
            'data' => $user
        ]);
    }
}
