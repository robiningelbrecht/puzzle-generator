<?php

use App\Infrastructure\Exception\ErrorRenderer;
use App\Infrastructure\Json;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;

return function (App $app) {
    // Add middleware to add response ContentType.
    $app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
        $response = $handler->handle($request);

        try {
            Json::decode($response->getBody());

            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Safe\Exceptions\JsonException) {
        }

        if (str_starts_with($response->getBody(), '<svg')) {
            return $response->withHeader('Content-Type', 'image/svg+xml');
        }

        return $response;
    });

    // Add Error middleware.
    $errorMiddleware = $app->addErrorMiddleware(true, false, false);

    /** @var \Slim\Handlers\ErrorHandler $errorHandler */
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->registerErrorRenderer('text/html', ErrorRenderer::class);
};
