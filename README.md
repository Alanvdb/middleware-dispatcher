# middleware-dispatcher

A basic PSR compliant middleware dispatcher.

## Overview

The `middleware-dispatcher` library provides a simple and extensible middleware dispatcher that complies with the PSR-15 standards. It allows you to handle HTTP server requests by processing a stack of middlewares.

## Features

- PSR-15 compliant
- Simple and easy-to-use API
- Supports any PSR-15 compatible middlewares
- Handles exceptions and invalid middlewares gracefully

## Installation

To install the `middleware-dispatcher` library, use Composer:

```sh
composer require alanvdb/middleware-dispatcher
```

## Usage

Here is an example of how to use the `middleware-dispatcher`:

```php
<?php

require 'vendor/autoload.php';

use AlanVdb\Dispatcher\Dispatcher;
use AlanVdb\Dispatcher\Factory\DispatcherFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;

class ExampleMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Add your middleware logic here
        return $handler->handle($request);
    }
}

$middlewares = [new ExampleMiddleware()];
$factory = new DispatcherFactory();
$dispatcher = $factory->createDispatcher($middlewares);

$request = new ServerRequest('GET', 'https://api.example.com/data');
$response = $dispatcher->handle($request);

echo $response->getBody();
```

## Testing

To run the tests, use PHPUnit. Ensure you have PHPUnit installed and execute the following command:

```sh
vendor/bin/phpunit
```

## Contributing

Contributions are welcome! Please follow these steps to contribute:

1. Fork the project
2. Create a branch for your feature (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Ensure your code follows PSR coding standards and includes relevant tests.

## License

This project is licensed under the MIT License. See the [MIT license](LICENSE) file for details.

## Issues and Feedback

If you encounter any issues or have feedback, please open an issue on the [GitHub repository](https://github.com/alanvdb/middleware-dispatcher/issues).

## Acknowledgements

- [PSR-15: HTTP Server Request Handlers](https://www.php-fig.org/psr/psr-15/)
- [Guzzle](https://guzzlephp.org/)