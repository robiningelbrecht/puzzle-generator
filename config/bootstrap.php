<?php

use App\Infrastructure\ContainerFactory;
use Slim\Factory\AppFactory;

AppFactory::setContainer(ContainerFactory::createInstance());
$app = AppFactory::create();

// Register routes
(require __DIR__.'/routes.php')($app);
// Register middleware
(require __DIR__.'/middleware.php')($app);

return $app;
