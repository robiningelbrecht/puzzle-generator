<?php

namespace App\Controller;

use App\Domain\RubiksCube\Algorithm;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\RubiksCube\View;
use App\Domain\Svg\Size as SvgSize;
use App\Domain\Svg\SvgBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class GitHubGameRequestHandler
{
    private const SPREADSHEET_RANGE = 'Scramble!A1';

    public function __construct(
        private readonly Environment $twig,
        private readonly \Google_Service_Sheets $googleServiceSheets
    ) {
    }

    public function renderCube(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $values = $this->googleServiceSheets->spreadsheets_values->get(
            $_ENV['GOOGLE_SPREADSHEET_ID'],
            self::SPREADSHEET_RANGE
        )->getValues();

        $cube = RubiksCubeBuilder::fromDefaults()
            ->build()
            ->scramble(Algorithm::fromString($values[0][0]));

        $svg = SvgBuilder::forCube($cube)
            ->withSize(SvgSize::fromOptionalInt(250))
            ->withView(View::tryFrom($request->getQueryParams()['view'] ?? ''))
            ->build();

        $response->getBody()->write($this->twig->render('svg.html.twig', [
            'svg' => $svg,
        ]));

        return $response;
    }

    public function doTurn(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $turn): ResponseInterface
    {
        Algorithm::fromString($turn);
        $values = $this->googleServiceSheets->spreadsheets_values->get(
            $_ENV['GOOGLE_SPREADSHEET_ID'],
            self::SPREADSHEET_RANGE
        )->getValues();

        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues([
            [
                $values[0][0].' '.$turn,
            ],
        ]);

        $this->googleServiceSheets->spreadsheets_values->update(
            $_ENV['GOOGLE_SPREADSHEET_ID'],
            self::SPREADSHEET_RANGE,
            $valueRange,
            ['valueInputOption' => 'USER_ENTERED']
        );

        return $response->withStatus(302)->withHeader('Location', 'https://github.com/robiningelbrecht');
    }
}
