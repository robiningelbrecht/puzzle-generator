<?php

namespace App\Tests\Unit\Domain\RubiksCube;

use App\Domain\Color;
use App\Domain\RubiksCube\Algorithm;
use App\Domain\RubiksCube\ColorScheme\ColorScheme;
use App\Domain\RubiksCube\CubeSize;
use App\Domain\RubiksCube\Mask;
use App\Domain\RubiksCube\Rotation\Rotation;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class RubiksCubeTest extends TestCase
{
    use MatchesSnapshots;

    public function testWithNullValues(): void
    {
        $cube = RubiksCubeBuilder::fromDefaults()
            ->withSize(null)
            ->withBaseColor(null)
            ->withMask(null)
            ->build();

        $this->assertEquals($cube, RubiksCubeBuilder::fromDefaults()->build());
        $this->assertMatchesJsonSnapshot(Json::encode($cube));
    }

    public function testWithAllValues(): void
    {
        $cube = RubiksCubeBuilder::fromDefaults()
            ->withSize(CubeSize::fromInt(4))
            ->withRotation(Rotation::fromXYZ(100, 50, 38))
            ->withColorScheme(ColorScheme::fromColors(
                Color::yellow(),
                Color::blue(),
                Color::green(),
                Color::white(),
                Color::orange(),
                Color::red()
            ))
            ->withBaseColor(Color::fromHexString('#EEEEEE'))
            ->withMask(Mask::OLL)
            ->build();

        $this->assertMatchesJsonSnapshot(Json::encode($cube));
    }

    public function testScrambleWithEmptyAlgorithm(): void
    {
        $cube = RubiksCubeBuilder::fromDefaults()->build();
        $cube->scramble(Algorithm::fromOptionalString());

        /** @var array $decodedCube */
        $decodedCube = Json::decode(Json::encode($cube));
        $this->assertEmpty($decodedCube['algorithm']);
    }

    public function testScramble(): void
    {
        $cube = RubiksCubeBuilder::fromDefaults()->build();
        $cube->scramble(Algorithm::fromString("F R U' R' U' R U R' F'"));
        $this->assertMatchesJsonSnapshot(Json::encode($cube));

        // Using the inverse algorithm should bring it back to a solved state.
        $cube->scramble(Algorithm::fromString("F R U' R' U R U R' F'"));
        $this->assertMatchesJsonSnapshot(Json::encode($cube));

        // Test all possible turns.
        $cube = RubiksCubeBuilder::fromDefaults()->build();
        $cube->scramble(Algorithm::fromString('U u F f R r D d L l B b M E S x y z'));
        $this->assertMatchesJsonSnapshot(Json::encode($cube));
    }
}
