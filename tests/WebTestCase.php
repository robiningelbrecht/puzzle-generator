<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

abstract class WebTestCase extends TestCase
{
    private App $app;
    private ContainerInterface $container;

    protected function createRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        $factory = $this->container->get(ServerRequestFactoryInterface::class);

        return $factory->createServerRequest($method, $uri, $serverParams);
    }

    protected function setup(): void
    {
        parent::setUp();

        $this->app = (require dirname(__DIR__).'/api/bootstrap.php');
        $this->container = $this->app->getContainer();
    }

    public function getApp(): App
    {
        return $this->app;
    }
}
