<?php
use PHPUnit\Framework\TestCase;
use StacGate\Core\Router;

final class RouterTest extends TestCase
{/**
 * @covers \StacGate\Core\Router::resolve
 */
    public function testRouteMatching(): void
    {
        $router = new Router();
        $router->get('/hello/{name}', 'Dummy', 'index');

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI']    = '/hello/Alice';

        $match = $router->resolve();

        $this->assertNotNull($match);
        $this->assertSame(['name' => 'Alice'], $match['params']);
    }
}
