<?php

namespace App\Services;

use App\Exceptions\RouteNotFoundException;
use Exception;

class Router
{
    protected array $routes = [];

    const GET = 'GET';
    const POST = 'POST';

    public function getAction(string $uri, string $requestMethod)
    {
        $action = $this->routes[$uri][$requestMethod] ?? null;

        if (!$action && isset($this->routes[$uri])) {
            return jsonResponse([
                'message' => 'This request method is not allowed.'
            ], 400);
        }

        return $action;
    }

    public function resolve(Request $request)
    {
        $action = $this->getAction($request->uri, $request->method);

        if (!$action) {
            throw new RouteNotFoundException('Route is incorrect');
        }

        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class)) {
                $class = Application::getContainer()->get($class);

                if (method_exists($class, $method)) {
                    return call_user_func_array([$class, $method], [$request]);
                }
            }
        }

        if (class_exists($action)) {
            $action = Application::getContainer()->get($action);

            if (is_callable($action)) {
                return $action($request);
            }
        }

        throw new RouteNotFoundException('Route does not exists');
    }

    public function get(string $route, array|string $resolver)
    {
        $this->register(self::GET, $route, $resolver);

        return $this;
    }

    public function post(string $route, array|string $resolver)
    {
        $this->register(self::POST, $route, $resolver);

        return $this;
    }

    public function register(string $method, string $route, array|string $resolver)
    {
        $this->routes[$route][$method] = $resolver;

        return $this;
    }

    public function getRoutes()
    {
        return $this->routes;
    }
}
