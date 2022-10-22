<?php

namespace App\Tests\Unit\Domain;

use App\Domain\Color;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\Svg;
use App\Domain\SvgSize;
use App\Infrastructure\Json;
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
