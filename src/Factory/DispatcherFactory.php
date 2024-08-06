<?php declare(strict_types=1);


namespace AlanVdb\Dispatcher\Factory;


use AlanVdb\Dispatcher\Definition\DispatcherFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use AlanVdb\Dispatcher\Dispatcher;


class DispatcherFactory implements DispatcherFactoryInterface
{
    public function createDispatcher(array $middlewares = []) : RequestHandlerInterface
    {
        return new Dispatcher($middlewares);
    }
}
