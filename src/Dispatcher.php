<?php declare(strict_types=1);

namespace AlanVdb\Dispatcher;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use AlanVdb\Dispatcher\Throwable\InvalidMiddlewareException;
use Throwable;

class Dispatcher implements RequestHandlerInterface
{
    private array $middlewares = [];
    private int $index = 0;

    public function __construct(array $middlewares = [])
    {
        foreach ($middlewares as $middleware) {
            if (!($middleware instanceof MiddlewareInterface || is_callable($middleware))) {
                throw new InvalidMiddlewareException("Invalid middleware.");
            }
        }
        $this->middlewares = $middlewares;
    }

    /**
     * Handle the incoming server request and process the middlewares.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws InvalidMiddlewareException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = null;

        try {
            if (!isset($this->middlewares[$this->index])) {
                throw new InvalidMiddlewareException("No middleware to process the request.");
            }

            $middleware = $this->middlewares[$this->index];
            $this->index++;

            return is_callable($middleware) 
                ? $middleware($request, $this)
                : $middleware->process($request, $this);
        } catch (Throwable $e) {
            throw $e;
        } finally {
            // Reset the index just before returning the response or if an exception is thrown
            $this->reset();
        }
    }

    /**
     * Add middlewares to the dispatcher.
     *
     * @param MiddlewareInterface|callable ...$middleware
     * @return self
     */
    public function addMiddlewares(MiddlewareInterface|callable ...$middleware): self
    {
        $this->middlewares = array_merge($this->middlewares, $middleware);
        return $this;
    }

    /**
     * Reset the index for middleware processing.
     */
    private function reset(): void
    {
        $this->index = 0;
    }
}
