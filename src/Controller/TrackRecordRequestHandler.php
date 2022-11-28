<?php

namespace App\Controller;

use App\Domain\RubiksCube\Algorithm;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\RubiksCube\View;
use App\Domain\Svg\SvgBuilder;
use App\Domain\TrackRecordFilePath;
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
        private readonly TrackRecordFilePath $trackRecordFilePath
    ) {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $header = ['puzzle', 'category', 'time', 'date', 'scramble', 'penalty', 'comment'];
        $csv = Reader::createFromPath($this->trackRecordFilePath->getPath());
        $csv->setHeaderOffset(null);
        $csv->setDelimiter(';');

        $stmt = Statement::create()
            ->offset(0)
            ->limit(50)
            ->orderBy(function (array $a, array $b) use ($header): int {
                if (count($a) !== count($header)) {
                    return 0;
                }
                if (count($b) !== count($header)) {
                    return 0;
                }
                $csvRowA = array_combine($header, $a);
                $csvRowB = array_combine($header, $b);

                if ($csvRowA['date'] < $csvRowB['date']) {
                    return 1;
                }
                if ($csvRowA['date'] > $csvRowB['date']) {
                    return -1;
                }

                return 0;
            });

        /** @var array $csvRows */
        $csvRows = iterator_to_array($stmt->process($csv));
        array_shift($csvRows);

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
