<?php declare(strict_types=1);


namespace AlanVdb\Dispatcher\Throwable;


use Throwable;
use InvalidArgumentException;


class InvalidMiddlewareException
    extends InvalidArgumentException
    implements Throwable
{}
