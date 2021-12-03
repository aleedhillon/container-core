<?php

namespace App\Services;

class Router
{
    protected $routes;

    const GET = 'GET';
    const POST = 'POST';

    protected function getAction(string $uri, string $requestMethod)
    {
        $action = $this->routes[$uri][$requestMethod] ?? null;

        if (!$action && isset($this->routes[$uri])) {
            return jsonResponse([
                'message' => 'This request method is not allowed.'
            ], 400);
        }

        return $action;
    }

    public function resolve(string $uri, string $requestMethod)
    {
        $action = $this->getAction($uri, $requestMethod);

        $request = new Request($requestMethod);

        if ($action) {
            if (is_array($action)) {
                $class = $action[0];
                $method = $action[1];
                $controller = new $class();
                
                echo $controller->$method($request);
            } else {
                echo (new $action)($request);
            }
        } else {
            echo notFound($request);
        }
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
