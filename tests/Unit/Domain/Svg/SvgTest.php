<?php

namespace App\Tests\Unit\Domain\Svg;

use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\Svg\Attribute;
use App\Domain\Svg\Group;
use App\Domain\Svg\Polygon;
use App\Domain\Svg\Size;
use App\Domain\Svg\Svg;
use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Color;
use App\Infrastructure\ValueObject\Point;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class SvgTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerialize(): void
    {
        $svg = Svg::default(RubiksCubeBuilder::fromDefaults()->build())
            ->withSize(Size::fromInt(100))
            ->withBackgroundColor(Color::orange())
            ->withGroups(
                Group::fromAttributes(Attribute::fromNameAndValue('name', 'value'))
                    ->addPolygon(Polygon::fromPointsAndFillColorAndStrokeColor(
                        [Point::fromXY(1, 2)],
                        Color::orange(),
                        Color::red()
                    ))
            );

        $this->assertMatchesJsonSnapshot(Json::encode($svg));
    }
}
