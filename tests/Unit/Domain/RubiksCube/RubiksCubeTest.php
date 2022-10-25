<?php

namespace App\Tests\Unit\Domain\RubiksCube;

use App\Domain\PuzzleException;
use App\Domain\RubiksCube\Algorithm;
use App\Domain\RubiksCube\ColorScheme\ColorScheme;
use App\Domain\RubiksCube\CubeSize;
use App\Domain\RubiksCube\Move;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Domain\RubiksCube\Turn\Turn;
use App\Domain\RubiksCube\Turn\TurnType;
use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Color;
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
            ->build();

        $this->assertEquals($cube, RubiksCubeBuilder::fromDefaults()->build());
        $this->assertMatchesJsonSnapshot(Json::encode($cube));
    }

    public function testWithAllValues(): void
    {
        $cube = RubiksCubeBuilder::fromDefaults()
            ->withSize(CubeSize::fromInt(4))
            ->withColorScheme(ColorScheme::fromColors(
                Color::yellow(),
                Color::blue(),
                Color::green(),
                Color::white(),
                Color::orange(),
                Color::red()
            ))
            ->withBaseColor(Color::fromHexString('#EEEEEE'))
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

        // Test all possible turns for 2 by 2.
        $cube = RubiksCubeBuilder::fromDefaults()
            ->withSize(CubeSize::fromInt(2))
            ->build();
        $cube->scramble(Algorithm::fromString('U F R D L B M E S x y z'));
        $this->assertMatchesJsonSnapshot(Json::encode($cube));

        // Test all possible turns for 1 by 1.
        $cube = RubiksCubeBuilder::fromDefaults()
            ->withSize(CubeSize::fromInt(1))
            ->build();
        $cube->scramble(Algorithm::fromString('U F R D L B M E S x y z'));
        $this->assertMatchesJsonSnapshot(Json::encode($cube));
    }

    public function testTurnTypeNone(): void
    {
        $cube = RubiksCubeBuilder::fromDefaults()
            ->withSize(CubeSize::fromInt(1))
            ->build();
        $cube->scramble(Algorithm::fromString('U F R D L B M E S x y z'));

        $this->assertEquals($cube, $cube->turn(Turn::fromMoveAndTurnTypeAndSlices('none', Move::B, TurnType::NONE, 0)));
    }

    public function testItShouldThrowOnInvalidSlices(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('The number of slices (3) must be smaller than the cube size (2)');

        $cube = RubiksCubeBuilder::fromDefaults()
            ->withSize(CubeSize::fromInt(2))
            ->build();

        $cube->scramble(Algorithm::fromString('3Rw'));
    }
}
