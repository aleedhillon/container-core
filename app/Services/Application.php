<?php

namespace App\Services;

use PDO;
use Throwable;

class Application
{
    protected Router $router;
    private static PDO $db;

    public function __construct(Router $router)
    {
        $this->bootstrap();
        $this->router = $router;
        static::$db = PDOConnection::make();
    }

    public static function getDB()
    {
        return static::$db;
    }

    public function run()
    {
        try {
            echo $this->router->resolve(new Request);
        } catch (Throwable $th) {
            exceptionToResponse($th);
        }
    }

    protected function bootstrap()
    {
        loadEnv();

        session_start([
            'name' => 'php_requests_session',
            'cookie_domain' => 'php-requests.test',
            'cookie_httponly' => true,
            'cookie_samesite' => true,
            'cookie_lifetime' => 120
        ]);
    }
}
