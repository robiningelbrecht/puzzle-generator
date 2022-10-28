<?php

namespace App\Tests\Unit\Domain\Svg;

use App\Domain\Svg\Polygon;
use App\Infrastructure\ValueObject\Color;
use App\Infrastructure\ValueObject\Point;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class PolygonTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerialize(): void
    {
        $this->assertMatchesJsonSnapshot(Polygon::fromPointsAndFillColorAndStrokeColor(
            [Point::fromXY(1, 2), Point::fromXY(3, 4)],
            Color::red(),
            Color::black()
        ));
    }

    public function testItShouldThrowWhenInvalidPoint(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid point provided');

        Polygon::fromPointsAndFillColorAndStrokeColor(
            ['invalid', Point::fromXY(3, 4)],
            Color::red(),
            Color::black()
        );
    }
}
