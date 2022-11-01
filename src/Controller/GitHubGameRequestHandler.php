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
use Zadorin\Airtable\Client;

class GitHubGameRequestHandler
{
    public function __construct(
        private readonly Environment $twig,
        private readonly Client $airtable
    ) {
    }

    public function renderCube(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        /** @var \Zadorin\Airtable\Record $record */
        $record = $this->airtable->table('Scrambles')
            ->select('Name')
            ->limit(1)
            ->execute()
            ->fetch();

        $cube = RubiksCubeBuilder::fromDefaults()
            ->build()
            ->scramble(Algorithm::fromString($record->getFields()['Name']));

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
        /** @var \Zadorin\Airtable\Record $record */
        $record = $this->airtable->table('Scrambles')
            ->select('Name')
            ->limit(1)
            ->execute()
        ->fetch();

        $record->setFields(['Name' => $record->getFields()['Name'].' '.$turn]);
        $this->airtable->table('Scrambles')->update($record)->execute();

        return $response->withStatus(302)->withHeader('Location', 'https://github.com/robiningelbrecht#jigsaw-lets-solve-this-rubuks-cube');
    }
}
