<?php

namespace App\Tests\Unit\Domain\Svg;

use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\Svg\Svg;
use App\Domain\Svg\SvgSize;
use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Color;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class SvgTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerialize(): void
    {
        $svg = Svg::default(RubiksCubeBuilder::fromDefaults()->build())
            ->withSize(SvgSize::fromInt(100))
            ->withBackgroundColor(Color::orange());

        $this->assertMatchesJsonSnapshot(Json::encode($svg));
    }
}
