<?php

use App\Controller\DocsRequestHandler;
use App\Controller\GitHubGameRequestHandler;
use App\Controller\RubiksCubeRequestHandler;
use App\Controller\TrackRecordRequestHandler;
use Slim\App;
use Slim\Handlers\Strategies\RequestResponseArgs;

return function (App $app) {
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

    $app->get('/', DocsRequestHandler::class.':handle');
    $app->get('/cube', RubiksCubeRequestHandler::class.':handle');
    $app->get('/track-record', TrackRecordRequestHandler::class.':handle');

    // GitHub game routes.
    $app->get('/github-game/cube', GitHubGameRequestHandler::class.':renderCube');
    $app->get('/github-game/turn/{turn}', GitHubGameRequestHandler::class.':doTurn');
};
