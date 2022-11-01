<?php

namespace App\Infrastructure;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Zadorin\Airtable\Client;

final class ContainerFactory
{
    public static function createInstance(): ContainerInterface
    {
        $builder = new ContainerBuilder();

        // Load env file.
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->safeLoad();

        $builder->addDefinitions([
            FilesystemLoader::class => \DI\create(FilesystemLoader::class)->constructor(dirname(__DIR__, 2) . '/templates'),
            Environment::class => \DI\create(Environment::class)->constructor(\DI\get(FilesystemLoader::class)),
            ServerRequestFactoryInterface::class => \DI\get(ServerRequestFactory::class),
            Client::class => function () {
                return new Client(
                    $_ENV['AIRTABLE_API_KEY'],
                    $_ENV['AIRTABLE_BASE'],
                );
            },
        ]);

        return $builder->build();
    }
}
