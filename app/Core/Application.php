<?php

namespace App\Core;

use PDO;
use Throwable;
use App\Core\Container;
use App\Services\Router;

class Application
{
    private static PDO $db;
    private static Container $container;

    public function __construct()
    {
        $this->bootstrap();


        // static::$db = PDOConnection::make();



        // static::$container->bind(Logger::class, FileLoggingService::class);

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

    public function run()
    {
        try {
            echo resolve(Router::class)->resolve();
        } catch (Throwable $th) {
            throw $th;
            exceptionToResponse($th);
        }
    }

    protected function bootstrap()
    {
        loadEnv();

        static::$container = new Container;

        foreach (getAllServiceProviders() as $serviceProvider) {
            (new $serviceProvider)->register(static::$container);
        }
    }
}
