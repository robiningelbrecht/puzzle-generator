<?php

namespace App\Infrastructure;

use App\Domain\TrackRecord\TrackRecordFilePath;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
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
            FilesystemLoader::class => \DI\create(FilesystemLoader::class)->constructor(dirname(__DIR__, 2).'/templates'),
            Environment::class => function (FilesystemLoader $filesystemLoader) {
                $twig = new Environment($filesystemLoader);
                $twig->addFilter(
                    new TwigFilter('strpad', function (string $string, int $length, string $pad_string = ' ', int $pad_type = STR_PAD_RIGHT) {
                        return str_pad($string, $length, $pad_string, $pad_type);
                    })
                );

                return $twig;
            },
            ServerRequestFactoryInterface::class => \DI\get(ServerRequestFactory::class),
            Client::class => function () {
                return new Client(
                    $_ENV['AIRTABLE_API_KEY'],
                    $_ENV['AIRTABLE_BASE'],
                );
            },
            TrackRecordFilePath::class => \DI\factory([TrackRecordFilePath::class, 'fromString'])->parameter(
                'path', dirname(__DIR__, 2).'/public/track-record.csv'
            ),
        ]);

        return $builder->build();
    }
}
