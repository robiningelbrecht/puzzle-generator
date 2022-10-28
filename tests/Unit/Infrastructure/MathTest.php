<?php

namespace App\Tests\Unit\Infrastructure;

use App\Domain\RubiksCube\Axis\Axis;
use App\Infrastructure\Math;
use App\Infrastructure\ValueObject\Position;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class MathTest extends TestCase
{
    use MatchesSnapshots;

    /**
     * @dataProvider provideRotations
     */
    public function testRotate(Position $position, Axis $axis, float $radians): void
    {
        $this->assertMatchesJsonSnapshot(Math::rotate($position, $axis, $radians));
    }

    /**
     * @dataProvider provideTranslations
     */
    public function testTranslate(Position $position, Position $translation): void
    {
        $this->assertMatchesJsonSnapshot(Math::translate($position, $translation));
    }

    /**
     * @dataProvider provideScales
     */
    public function testScale(Position $position, float $factor): void
    {
        $this->assertMatchesJsonSnapshot(Math::scale($position, $factor));
    }

    /**
     * @dataProvider provideTransScales
     */
    public function testTransScale(Position $position, Position $translation, float $factor): void
    {
        $this->assertMatchesJsonSnapshot(Math::transScale($position, $translation, $factor));
    }

    /**
     * @dataProvider provideProjections
     */
    public function testProject(Position $position, int $distance): void
    {
        $this->assertMatchesJsonSnapshot(Math::project($position, $distance));
    }

    public function provideRotations(): array
    {
        return [
            [Position::fromXYZ(1, 2, 3), Axis::X, 3.56],
            [Position::fromXYZ(1, 2, 3), Axis::Y, 3.56],
            [Position::fromXYZ(1, 2, 3), Axis::Z, 3.56],
            [Position::fromXYZ(103, -24, 320), Axis::Z, 10.01],
            [Position::fromXYZ(10, 256, 30), Axis::X, 0],
            [Position::fromXYZ(-23, 98, 33), Axis::Y, 56],
        ];
    }

    public function provideTranslations(): array
    {
        return [
            [Position::fromXYZ(1, 2, 3), Position::fromXYZ(1, 2, 3)],
            [Position::fromXYZ(-1, -2, -3), Position::fromXYZ(1, 2, 3)],
            [Position::fromXYZ(1, 2, 3), Position::fromXYZ(-1, -2, -3)],
            [Position::fromXYZ(-1, -2, -3), Position::fromXYZ(-1, -2, -3)],
        ];
    }

    public function provideScales(): array
    {
        return [
            [Position::fromXYZ(1, 2, 3), 3.56],
            [Position::fromXYZ(1, 2, 3), 98],
            [Position::fromXYZ(-1, -2, -3), 3.56],
            [Position::fromXYZ(1, -2, 3), 8.56],
        ];
    }

    public function provideTransScales(): array
    {
        return [
            [Position::fromXYZ(1, 2, 3), Position::fromXYZ(1, 2, 3), 3.56],
            [Position::fromXYZ(-1, -2, -3), Position::fromXYZ(1, 2, 3), 3.56],
            [Position::fromXYZ(1, 2, 3), Position::fromXYZ(-1, -2, -3), 3.56],
            [Position::fromXYZ(-1, -2, -3), Position::fromXYZ(-1, -2, -3), 3.56],
        ];
    }

    public function provideProjections(): array
    {
        return [
            [Position::fromXYZ(1, 2, 3), 3],
            [Position::fromXYZ(-1, -2, -3), 3],
            [Position::fromXYZ(10, 12, 33), 3],
            [Position::fromXYZ(1, 2, 3), 8],
        ];
    }
}
