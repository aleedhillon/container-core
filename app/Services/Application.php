<?php

namespace App\Services;

use App\Contracts\Logger;
use App\Core\Container;
use PDO;
use Throwable;

class Application
{
    private static PDO $db;
    private static Container $container;

    public function __construct()
    {
        $this->bootstrap();
        static::$db = PDOConnection::make();

        static::$container = new Container;

        static::$container->set(Logger::class, FileLoggingService::class);

        // static::$container->set(Log::class, function(Container $container) {
        //     return new Log($container->get(Logger::class));
        // });
    }

    public static function getContainer()
    {
        return static::$container;
    }

    public static function getDB()
    {
        return static::$db;
    }

    public function run(Router $router)
    {
        try {
            echo $router->resolve(new Request);
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
