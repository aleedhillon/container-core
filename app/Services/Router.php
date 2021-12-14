<?php

namespace App\Services;

use App\Exceptions\RouteNotFoundException;

class Router
{
    protected array $routes = [];
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    const GET = 'GET';
    const POST = 'POST';

    public function getAction(string $uri, string $requestMethod)
    {
        $parameter = null;

        $parts = explode('/', $uri);
        if(isset($parts[2]) && $parts[2] !== '') {
            $parameter = $parts[2];
        }

        $uri = '/'.$parts[1];

        if(isset($this->routes[$uri][$requestMethod])) {
            dd($this->routes[$uri][$requestMethod]);
        }

        dd('not good');
        $action = $this->routes[$uri][$requestMethod] ?? null;

        if (!$action && isset($this->routes[$uri])) {
            return jsonResponse([
                'message' => 'This request method is not allowed.'
            ], 400);
        }

        return $action;
    }

    public function resolve()
    {
        $action = $this->getAction($this->request->uri, $this->request->method);

        if (!$action) {
            throw new RouteNotFoundException('Route is incorrect');
        }

        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class)) {
                $class = resolve($class);

                if (method_exists($class, $method)) {
                    return call_user_func_array([$class, $method], []);
                }
            }
        }

        if (class_exists($action)) {
            $action = resolve($action);

            if (is_callable($action)) {
                return $action();
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
        $parts = explode('{', $route);
        $route = rtrim($parts[0], '/');
        $route = $route === '' ? '/' : $route;

        $this->routes[$route][$method] = [
            'action' => $resolver,
            'parameter' => null
        ];

        if (isset($parts[1])) {
            $parameter = explode('}', $parts[1])[0];

            if ($parameter !== '') {
                $this->routes[$route]['parameter'] = $parameter;
            }
        }

        return $this;
    }

    public function getRoutes()
    {
        return $this->routes;
    }
}
