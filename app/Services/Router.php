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

        if ($action) {
            $requestData = $this->getRequestData($requestMethod);
            if (is_array($action)) {
                $class = $action[0];
                $method = $action[1];
                $controller = new $class();
                return $controller->$method($requestData);
            } else {
                return (new $action)($requestData);
            }
        } else {
            return notFound();
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

    protected function getRequestData(string $httpMethod)
    {
        if ($httpMethod == self::POST) {
            return $this->getPostRequestData();
        }

        if ($httpMethod == self::GET) {
            return $this->getGetRequestData();
        }

        return [];
    }

    protected function getGetRequestData()
    {
        $data = [];
        $headers = getallheaders();
        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
        } else {
            $data = $_GET;
        }

        return $data;
    }

    protected function getPostRequestData()
    {
        $data = [];
        $headers = getallheaders();
        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
        } else {
            $data = $_POST;
        }

        return $data;
    }
}
