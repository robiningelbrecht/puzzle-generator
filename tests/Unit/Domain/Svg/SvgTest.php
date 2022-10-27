<?php

namespace App\Tests\Unit\Domain\Svg;

use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\Svg\Size;
use App\Domain\Svg\SvgBuilder;
use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Color;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class SvgTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerialize(): void
    {
        $svg = SvgBuilder::forCube(RubiksCubeBuilder::fromDefaults()->build())
            ->withSize(Size::fromInt(100))
            ->withBackgroundColor(Color::orange())
            ->build();

        $this->assertMatchesJsonSnapshot(Json::encode($svg));
    }
}
