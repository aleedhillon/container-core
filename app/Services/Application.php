<?php

namespace App\Services;

use App\Contracts\Logger;
use App\Core\Container;
use PDO;
use Throwable;

class Application
{
    protected Router $router;
    private static PDO $db;
    public static Container $container;

    public function __construct(Router $router)
    {
        $this->bootstrap();
        $this->router = $router;
        static::$db = PDOConnection::make();

        static::$container = new Container;

        static::$container->set(Logger::class, function($container) {
            return new FileLoggingService;
        });

        static::$container->set(Log::class, function(Container $container) {
            return new Log($container->get(Logger::class));
        });
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
