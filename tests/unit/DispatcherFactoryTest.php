<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use AlanVdb\Dispatcher\Factory\DispatcherFactory;
use AlanVdb\Dispatcher\Dispatcher;
use Psr\Http\Server\MiddlewareInterface;

class DispatcherFactoryTest extends TestCase
{
    public function testCreateDispatcherWithNoMiddlewares()
    {
        $factory = new DispatcherFactory();
        $dispatcher = $factory->createDispatcher();

        $this->assertInstanceOf(RequestHandlerInterface::class, $dispatcher);
        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
    }

    public function testCreateDispatcherWithMiddlewares()
    {
        $middleware = $this->createMock(MiddlewareInterface::class);
        $factory = new DispatcherFactory();
        $dispatcher = $factory->createDispatcher([$middleware]);

        $this->assertInstanceOf(RequestHandlerInterface::class, $dispatcher);
        $this->assertInstanceOf(Dispatcher::class, $dispatcher);

        // Ensure the dispatcher has the provided middleware
        $reflection = new \ReflectionClass($dispatcher);
        $property = $reflection->getProperty('middlewares');
        $property->setAccessible(true);
        $middlewares = $property->getValue($dispatcher);

        $this->assertCount(1, $middlewares);
        $this->assertSame($middleware, $middlewares[0]);
    }
}
