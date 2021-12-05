<?php

namespace App\Services;

use App\Models\User;
use PDO;
use Throwable;

class DB
{
    protected $connection;

    public function __construct()
    {
        $driver = env('DB_DRIVER');
        $host = env('DB_HOST');
        $dbname = env('DB_NAME');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $dsn = "{$driver}:host={$host};dbname={$dbname};charset=UTF8";

        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function RawQuery($query)
    {
        return $this->connection->query($query)->fetchAll();
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
        $keys = array_keys($data);

        $queryStatement = "INSERT INTO {$table} ({$this->formatAttributes($keys)}) values ({$this->formatValuePlaceholders($keys)})";

        $query = $this->connection->prepare($queryStatement);

        $query->execute($data);

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
            return is_numeric($attribute) ? $attribute : $this->connection->quote($attribute);
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
