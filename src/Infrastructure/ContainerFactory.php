<?php

namespace App\Infrastructure;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
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

        // Load env file.
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->safeLoad();

        $builder->addDefinitions([
            FilesystemLoader::class => \DI\create(FilesystemLoader::class)->constructor(dirname(__DIR__, 2).'/templates'),
            Environment::class => \DI\create(Environment::class)->constructor(\DI\get(FilesystemLoader::class)),
            ServerRequestFactoryInterface::class => \DI\get(ServerRequestFactory::class),
            \Google_Client::class => function () {
                $client = new \Google_Client();
                $client->setApplicationName('Google Sheets API');
                $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
                $client->setAccessType('offline');
                $client->setAuthConfig([
                    'type' => 'service_account',
                    'project_id' => $_ENV['GOOGLE_PROJECT_ID'],
                    'private_key_id' => $_ENV['GOOGLE_PRIVATE_KEY_ID'],
                    'private_key' => $_ENV['GOOGLE_PRIVATE_KEY'],
                    'client_email' => $_ENV['GOOGLE_CLIENT_EMAIL'],
                    'client_id' => $_ENV['GOOGLE_CLIENT_ID'],
                    'auth_uri' => $_ENV['GOOGLE_AUTH_URI'],
                    'token_uri' => $_ENV['GOOGLE_TOKEN_URI'],
                    'auth_provider_x509_cert_url' => $_ENV['GOOGLE_AUTH_PROVIDER_X509_CERT_URL'],
                    'client_x509_cert_url' => $_ENV['GOOGLE_CLIENT_X509_CERT_URL'],
                ]);

                return $client;
            },
            \Google_Service_Sheets::class => \DI\create(\Google_Service_Sheets::class)->constructor(\DI\get(\Google_Client::class)),
        ]);

        return $builder->build();
    }
}
