<?php

namespace App\Controller;

use App\Domain\RubiksCube\Algorithm;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\RubiksCube\View;
use App\Domain\Svg\Size as SvgSize;
use App\Domain\Svg\SvgBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TANIOS\Airtable\Airtable;
use Twig\Environment;
use Zadorin\Airtable\Client;

class GitHubGameRequestHandler
{
    private const SPREADSHEET_RANGE = 'Scramble!A1';

    public function __construct(
        private readonly Environment $twig,
        private readonly Client $airtable
    )
    {
    }

    public function renderCube(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $recordset = $this->airtable->table('Scrambles')
            ->select('Name')
            ->limit(1)
            ->execute();

        $cube = RubiksCubeBuilder::fromDefaults()
            ->build()
            ->scramble(Algorithm::fromString($recordset->fetch()->getFields()['Name']));

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
        $recordset = $this->airtable->table('Scrambles')
            ->select('Name')
            ->limit(1)
            ->execute();

        $record = $recordset->fetch();

        $record->setFields(['Name' => $recordset->asArray()[0]['Name'] . ' ' . $turn]);
        $this->airtable->table('Scrambles')->update($record);

        return $response->withStatus(302)->withHeader('Location', 'https://github.com/robiningelbrecht');
    }
}