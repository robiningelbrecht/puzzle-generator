<?php

namespace App\Controller;

use App\Domain\Color;
use App\Domain\RubiksCube\Algorithm;
use App\Domain\RubiksCube\ColorScheme\ColorSchemeBuilder;
use App\Domain\RubiksCube\CubeSize;
use App\Domain\RubiksCube\Mask;
use App\Domain\RubiksCube\Rotation\RotationBuilder;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\Svg;
use App\Domain\SvgSize;
use App\Infrastructure\Json;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class RubiksCubeRequestHandler
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
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

        if (isset($params['json'])) {
            $response->getBody()->write(Json::encode($svg));

            return $response;
        }

        $response->getBody()->write($this->twig->render('cube.html.twig'));

        return $response;
    }
}
