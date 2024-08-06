<?php declare(strict_types=1);


namespace AlanVdb\Dispatcher\Definition;


use Psr\Http\Server\RequestHandlerInterface;


interface DispatcherFactoryInterface
{
    public function createDispatcher(array $middlewares = []) : RequestHandlerInterface;
}