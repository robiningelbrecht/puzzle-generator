<?php

use App\Controller\RubiksCubeRequestHandler;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;

return function (App $app) {
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

    $app->get('/cube', RubiksCubeRequestHandler::class.':handle');
};
