<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use AlanVdb\Dispatcher\Dispatcher;
use AlanVdb\Dispatcher\Throwable\InvalidMiddlewareException;

class DispatcherTest extends TestCase
{
    public function testConstructWithInvalidMiddlewareThrowsException()
    {
        $this->expectException(InvalidMiddlewareException::class);
        $dispatcher = new Dispatcher(['invalid middleware']);
    }

    public function testHandleWithNoMiddlewaresThrowsException()
    {
        $this->expectException(InvalidMiddlewareException::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $dispatcher = new Dispatcher();
        $dispatcher->handle($request);
    }

    public function testHandleWithValidMiddleware()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $middleware = $this->createMock(MiddlewareInterface::class);

        $middleware->expects($this->once())
                   ->method('process')
                   ->with($request, $this->isInstanceOf(RequestHandlerInterface::class))
                   ->willReturn($response);

        $dispatcher = new Dispatcher([$middleware]);
        $result = $dispatcher->handle($request);

        $this->assertSame($response, $result);
    }

    public function testHandleWithCallableMiddleware()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $middleware = function ($request, $handler) use ($response) {
            return $response;
        };

        $dispatcher = new Dispatcher([$middleware]);
        $result = $dispatcher->handle($request);

        $this->assertSame($response, $result);
    }

    public function testAddMiddlewares()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $middleware = $this->createMock(MiddlewareInterface::class);

        $middleware->expects($this->once())
                   ->method('process')
                   ->with($request, $this->isInstanceOf(RequestHandlerInterface::class))
                   ->willReturn($response);

        $dispatcher = new Dispatcher();
        $dispatcher->addMiddlewares($middleware);
        $result = $dispatcher->handle($request);

        $this->assertSame($response, $result);
    }

    public function testReset()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $middleware = $this->createMock(MiddlewareInterface::class);

        $middleware->expects($this->exactly(2))
                   ->method('process')
                   ->with($request, $this->isInstanceOf(RequestHandlerInterface::class))
                   ->willReturn($response);

        $dispatcher = new Dispatcher([$middleware]);
        
        // First call to handle
        $result1 = $dispatcher->handle($request);
        $this->assertSame($response, $result1);

        // Second call to handle after reset
        $result2 = $dispatcher->handle($request);
        $this->assertSame($response, $result2);
    }
}
