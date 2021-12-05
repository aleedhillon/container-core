<?php

namespace App\Services;

use PDO;
use Throwable;

class PDOConnection
{
    public static function make()
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

            return new PDO($dsn, $username, $password, $options);
        } catch (Throwable $th) {
            throw $th;
        }
    }
}
