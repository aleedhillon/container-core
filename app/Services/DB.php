<?php

namespace App\Services;

use PDO;
use Throwable;

class DB
{
    protected $host = 'localhost';
    protected $dbname = 'custom_php';
    protected $username = 'root';
    protected $password = 'toor';
    protected $connection;

    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=UTF8";

        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function select(string $table, array $attributes = ['*'])
    {
        $queryStatement = "SELECT {$this->formatAttributes($attributes)} FROM `{$table}` ORDER BY `id`";
        $query = $this->connection->query($queryStatement);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(string $table, int $id, array $attributes = ['*'])
    {
        return $this->whereFirst($table, [
            'id' => $id
        ]);
    }

    public function whereFirst(string $table, array $search, array $attributes = ['*'])
    {
        $whereQuery = $this->formatWhereCluases($search);

        $queryStatement = "SELECT {$this->formatAttributes($attributes)} FROM `{$table}` WHERE {$whereQuery} LIMIT 1";
        $query = $this->connection->query($queryStatement);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function where(string $table, array $search, array $attributes = ['*'])
    {
        $whereQuery = $this->formatWhereCluases($search);

        $queryStatement = "SELECT {$this->formatAttributes($attributes)} FROM `{$table}` WHERE {$whereQuery}";
        $query = $this->connection->query($queryStatement);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertOne(string $table, array $data)
    {
        $attributes = $this->formatAttributes(array_keys($data));

        $values = $this->formatValues(array_values($data));

        $queryStatement = "INSERT INTO {$table} ({$attributes}) values ({$values})";

        $query = $this->connection->prepare($queryStatement);

        $query->execute();

        return $this->connection->lastInsertId('id');
    }

    private function formatAttributes(array $attributes)
    {
        return implode(',', array_map(function ($attribute) {
            return $attribute == '*' ? $attribute : "`{$attribute}`";
        }, $attributes));
    }

    private function formatValues(array $values)
    {
        return implode(',', array_map(function ($attribute) {
            return is_numeric($attribute) ? $attribute : "'{$attribute}'";
        }, $values));
    }

    private function formatValuePlaceholders(array $values)
    {
        return implode(',', array_map(function ($attribute) {
            return ":{$attribute}";
        }, $values));
    }

    private function formatWhereCluases(array $search)
    {
        return implode(' AND ', array_map(function ($value, $key) {
            return "`{$key}`='{$value}'";
        }, array_values($search), array_keys($search)));
    }
}
