<?php

namespace Unit;

use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Exceptions\RouteNotFoundException;
use App\Services\Request;
use App\Services\Router;
use PHPUnit\Framework\TestCase;
use stdClass;

class RouterTest extends TestCase
{
    private $router;

    protected function setUp(): void
    {
        $this->router = new Router;
    }

    /**
     * @test
     * @dataProvider routeCases
     */
    public function test_it_registers_get_route(string $route, string|array $resolver)
    {
        $method = Router::GET;

        $this->router->get($route, $resolver);

        $resolved = $this->router->getRoutes()[$route][$method];

        $this->assertEquals($resolver, $resolved);
    }

    public function routeCases()
    {
        return [
            ['route1', 'resolver1'],
            ['route2', 'resolver2'],
            ['route3', 'resolver3'],
            ['posts', [PostController::class, 'index']],
            ['users', [stdClass::class, 'idex']]
        ];
    }

    public function test_it_registers_post_route()
    {
        $method = Router::POST;
        $route = 'something';
        $resolver = [PostController::class, 'store'];

        $this->router->post($route, $resolver);

        $resolved = $this->router->getAction($route, $method);

        $this->assertEquals($resolver, $resolved, 'Router registering post method does not work');
    }

    public function test_rotues_are_empty_when_router_is_initialized()
    {
        $router = new Router;

        $this->assertEmpty($router->getRoutes());
    }

    public function test_get_method_is_chainable()
    {
        $this->assertEquals($this->router, $this->router->get('somethingelse', 'somethingElse'));
    }

    public function test_post_method_is_chainable()
    {
        $this->assertEquals($this->router, $this->router->post('somethingelse', 'somethingElse'));
    }

    public function test_throws_route_not_found_exception_on_undefined_route()
    {
        $this->expectException(RouteNotFoundException::class);
        $this->router->resolve(new Request);
    }

    protected function tearDown(): void
    {
        unset($this->router);
    }
}
