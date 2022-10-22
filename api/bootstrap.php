<?php

use App\Infrastructure\ContainerFactory;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

AppFactory::setContainer(ContainerFactory::createInstance());
$app = AppFactory::create();

// Register routes
(require __DIR__.'/routes.php')($app);

$app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
    $params = $request->getQueryParams();
    $response = $handler->handle($request);

    if (isset($params['json'])) {
        return $response->withHeader('Content-Type', 'application/json');
    }

    return $response->withHeader('Content-Type', 'image/svg+xml');
});

return $app;
