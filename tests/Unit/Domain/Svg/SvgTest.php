<?php

namespace App\Tests\Unit\Domain\Svg;

use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\Rotation;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\RubiksCube\View;
use App\Domain\Svg\Size;
use App\Domain\Svg\SvgBuilder;
use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Color;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class SvgTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerializeWithView3D(): void
    {
        $svg = SvgBuilder::forCube(RubiksCubeBuilder::fromDefaults()->build())
            ->withSize(Size::fromInt(100))
            ->withBackgroundColor(Color::orange())
            ->withRotations(Rotation::fromAxisAndValue(Axis::Y, 30))
            ->withView(View::THREE_D)
            ->build();

        $this->assertMatchesJsonSnapshot(Json::encode($svg));
    }

    public function testJsonSerializeWithViewTop(): void
    {
        $svg = SvgBuilder::forCube(RubiksCubeBuilder::fromDefaults()->build())
            ->withSize(Size::fromInt(100))
            ->withBackgroundColor(Color::orange())
            ->withRotations(Rotation::fromAxisAndValue(Axis::Y, 30))
            ->withView(View::TOP)
            ->build();

        $this->assertMatchesJsonSnapshot(Json::encode($svg));
    }

    public function testJsonSerializeWithViewNet(): void
    {
        $svg = SvgBuilder::forCube(RubiksCubeBuilder::fromDefaults()->build())
            ->withSize(Size::fromInt(100))
            ->withBackgroundColor(Color::orange())
            ->withRotations(Rotation::fromAxisAndValue(Axis::Y, 30))
            ->withView(View::NET)
            ->build();

        $this->assertMatchesJsonSnapshot(Json::encode($svg));
    }

    public function testJsonSerializeWithNullValues(): void
    {
        $svg = SvgBuilder::forCube(RubiksCubeBuilder::fromDefaults()->build())
            ->withSize(null)
            ->withBackgroundColor(null)
            ->withRotations()
            ->withView(null)
            ->build();

        $this->assertMatchesJsonSnapshot(Json::encode($svg));
    }
}
