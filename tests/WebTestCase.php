<?php

namespace App\Tests;

use App\Domain\TrackRecordFilePath;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Zadorin\Airtable\Client;

abstract class WebTestCase extends TestCase
{
    private App $app;
    /** @var \DI\Container */
    private ContainerInterface $container;
    protected MockObject $airtableClient;

    protected function createRequest(string $method, string $uri, array $serverParams = []): ServerRequestInterface
    {
        /** @var ServerRequestFactoryInterface $factory */
        $factory = $this->container->get(ServerRequestFactoryInterface::class);

        return $factory->createServerRequest($method, $uri, $serverParams);
    }

    protected function setup(): void
    {
        parent::setUp();

        $this->app = (require dirname(__DIR__).'/config/bootstrap.php');
        $this->container = $this->app->getContainer();

        // Mock AirTable client.
        $this->airtableClient = $this->createMock(Client::class);
        $this->container->set(Client::class, $this->airtableClient);

        // Use test track record csv.
        $this->container->set(TrackRecordFilePath::class, TrackRecordFilePath::fromString(__DIR__.'/Integration/track-record.csv'));
    }

    public function getApp(): App
    {
        return $this->app;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
