<?php

use App\Infrastructure\ErrorRenderer;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;

return function (App $app) {
    // Add middleware to add response ContentType.
    $app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
        $params = $request->getQueryParams();
        $response = $handler->handle($request);

        if (isset($params['json'])) {
            return $response->withHeader('Content-Type', 'application/json');
        }

        return $response->withHeader('Content-Type', 'image/svg+xml');
    });

    // Add Error middleware.
    $errorMiddleware = $app->addErrorMiddleware(true, false, false);

    /** @var \Slim\Handlers\ErrorHandler $errorHandler */
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->registerErrorRenderer('text/html', ErrorRenderer::class);
};