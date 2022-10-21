<?php

use App\Color;
use App\RubiksCube\Algorithm;
use App\RubiksCube\ColorScheme\ColorSchemeBuilder;
use App\RubiksCube\CubeSize;
use App\RubiksCube\Mask;
use App\RubiksCube\Rotation\RotationBuilder;
use App\RubiksCube\RubiksCubeBuilder;
use App\Svg;
use App\SvgSize;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__.'/../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    FilesystemLoader::class => DI\create(FilesystemLoader::class)->constructor(dirname(__DIR__).'/templates'),
    Environment::class => DI\create(Environment::class)->constructor(DI\get(FilesystemLoader::class)),
]);
$container = $builder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
    $params = $request->getQueryParams();
    $svgParams = $params['svg'] ?? null;
    $cubeParams = $params['cube'] ?? null;

    $cubeBuilder = RubiksCubeBuilder::fromDefaults();
    if (null !== $cubeParams) {
        $cubeBuilder
            ->withSize(CubeSize::fromOptionalInt($cubeParams['size'] ?? null))
            ->withRotation(
                RotationBuilder::fromDefaults()
                    ->withX($cubeParams['rotation']['x'] ?? null)
                    ->withY($cubeParams['rotation']['y'] ?? null)
                    ->withZ($cubeParams['rotation']['z'] ?? null)
                    ->build()
            )
            ->withColorScheme(
                ColorSchemeBuilder::fromDefaults()
                    ->withColorForU(Color::fromOptionalHexString($cubeParams['colorScheme']['U'] ?? null))
                    ->withColorForR(Color::fromOptionalHexString($cubeParams['colorScheme']['R'] ?? null))
                    ->withColorForF(Color::fromOptionalHexString($cubeParams['colorScheme']['F'] ?? null))
                    ->withColorForD(Color::fromOptionalHexString($cubeParams['colorScheme']['D'] ?? null))
                    ->withColorForL(Color::fromOptionalHexString($cubeParams['colorScheme']['L'] ?? null))
                    ->withColorForB(Color::fromOptionalHexString($cubeParams['colorScheme']['B'] ?? null))
                    ->build()
            )
            ->withBaseColor(Color::fromOptionalHexString($cubeParams['baseColor'] ?? null))
            ->withMask(Mask::tryFrom($cubeParams['mask'] ?? ''))
            ->build();
    }

    $cube = $cubeBuilder
        ->build()
        ->scramble(Algorithm::fromOptionalString($cubeParams['algorithm'] ?? null));

    $svg = Svg::default($cube)
        ->withSize(SvgSize::fromOptionalInt($svgParams['size'] ?? null))
        ->withBackgroundColor(Color::fromOptionalHexString($svgParams['backgroundColor'] ?? null));

    /** @var Environment $twig */
    $twig = $this->get(Environment::class);
    $response->getBody()->write($twig->render('cube.html.twig'));

    return $response->withHeader('Content-Type', 'image/svg+xml');
    // $response->getBody()->write(json_encode($svg));
    // return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
