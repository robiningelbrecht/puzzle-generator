<?php

namespace App\Infrastructure;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class ContainerFactory
{
    public static function createInstance(): ContainerInterface
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions([
            FilesystemLoader::class => \DI\create(FilesystemLoader::class)->constructor(dirname(__DIR__, 2).'/templates'),
            Environment::class => \DI\create(Environment::class)->constructor(\DI\get(FilesystemLoader::class)),
            ServerRequestFactoryInterface::class => \DI\get(ServerRequestFactory::class),
        ]);

        return $builder->build();
    }
}
