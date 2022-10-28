<?php

namespace App\Controller;

use App\Domain\RubiksCube\Algorithm;
use App\Domain\RubiksCube\ColorScheme\ColorSchemeBuilder;
use App\Domain\RubiksCube\Mask;
use App\Domain\RubiksCube\Rotation;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\RubiksCube\Size as CubeSize;
use App\Domain\RubiksCube\View;
use App\Domain\Svg\Size as SvgSize;
use App\Domain\Svg\SvgBuilder;
use App\Infrastructure\Exception\PuzzleException;
use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Color;
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
        $cubeParams = $params['cube'] ?? null;
        unset($params['cube']);

        $cubeBuilder = RubiksCubeBuilder::fromDefaults();
        if (null !== $cubeParams) {
            $cubeBuilder
                ->withSize(CubeSize::fromOptionalInt($cubeParams['size'] ?? null))
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
                ->withMask(Mask::tryFrom($cubeParams['mask'] ?? ''));
        }

        if (!empty($cubeParams['case']) && !empty($cubeParams['algorithm'])) {
            throw new PuzzleException('You can ony provide a "case" or an "algorithm", but not both.');
        }

        $algorithm = Algorithm::fromOptionalString($cubeParams['algorithm'] ?? null);
        if ($algorithm->isEmpty()) {
            $algorithm = Algorithm::fromOptionalString($cubeParams['case'] ?? null)->reverse();
        }

        $cube = $cubeBuilder
            ->build()
            ->scramble($algorithm);

        $svg = SvgBuilder::forCube($cube)
            ->withSize(SvgSize::fromOptionalInt($params['size'] ?? null))
            ->withBackgroundColor(Color::fromOptionalHexString($params['backgroundColor'] ?? null))
            ->withRotations(...Rotation::fromMap(!empty($params['rotations']) && is_array($params['rotations']) ? $params['rotations'] : []))
            ->withView(View::tryFrom($params['view'] ?? ''))
            ->build();

        if (isset($params['json'])) {
            $response->getBody()->write(Json::encode($svg));

            return $response;
        }

        $response->getBody()->write($this->twig->render('svg.html.twig', [
            'svg' => $svg,
        ]));

        return $response;
    }
}
