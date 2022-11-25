<?php

namespace App\Controller;

use App\Domain\RubiksCube\Algorithm;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\RubiksCube\View;
use App\Domain\Svg\SvgBuilder;
use App\Infrastructure\ValueObject\Time;
use League\Csv\Reader;
use League\Csv\Statement;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Safe\DateTimeImmutable;
use Twig\Environment;

class TrackRecordRequestHandler
{
    public function __construct(
        private readonly Environment $twig,
    ) {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $csv = Reader::createFromPath(dirname(__DIR__, 2).'/public/track-record.csv');
        $csv->setHeaderOffset(null);
        $csv->setDelimiter(';');

        $stmt = Statement::create();
        // ->offset(0)
        // ->limit(250);

        /** @var array $csvRows */
        $csvRows = iterator_to_array($stmt->process($csv));
        array_shift($csvRows);
        $header = ['puzzle', 'category', 'time', 'date', 'scramble', 'penalty', 'comment'];

        $response->getBody()->write($this->twig->render('track-record.html.twig', [
            'rows' => array_map(function (array $csvRow) use ($header): array {
                $csvRow = array_combine($header, $csvRow);
                $algorithm = Algorithm::fromString($csvRow['scramble']);
                $cube = RubiksCubeBuilder::fromDefaults()
                    ->build()
                    ->scramble(Algorithm::fromString($algorithm));

                return [
                    'date' => (new DateTimeImmutable())->setTimestamp((int) ceil($csvRow['date'] / 1000)),
                    'time' => Time::fromMilliSeconds($csvRow['time']),
                    'scramble' => $algorithm,
                    'method' => $csvRow['category'],
                    'svgThreeD' => $this->twig->render('svg.html.twig', [
                        'svg' => SvgBuilder::forCube($cube)
                            ->withView(View::THREE_D)
                            ->build(),
                    ]),
                    'svgNet' => $this->twig->render('svg.html.twig', [
                        'svg' => SvgBuilder::forCube($cube)
                            ->withView(View::NET)
                            ->build(),
                    ]),
                ];
            }, $csvRows),
        ]));

        return $response;
    }
}
