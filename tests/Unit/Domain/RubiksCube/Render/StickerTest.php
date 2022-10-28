<?php

namespace App\Tests\Unit\Domain\RubiksCube\Render;

use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\Render\Sticker;
use App\Domain\RubiksCube\Rotation;
use App\Domain\RubiksCube\RubiksCubeBuilder;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class StickerTest extends TestCase
{
    use MatchesSnapshots;

    public function testCreateForCubeAndDistance(): void
    {
        $cube = RubiksCubeBuilder::fromDefaults()->build();
        $stickers = Sticker::createForCubeAndDistance($cube, 5, [
            Rotation::fromAxisAndValue(Axis::Y, Rotation::DEFAULT_Y),
            Rotation::fromAxisAndValue(Axis::X, Rotation::DEFAULT_X),
        ]);

        $this->assertMatchesJsonSnapshot(Json::encode($stickers));
    }

    public function testItShouldThrowOnEmptyRotations(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Rotations cannot be empty');

        $cube = RubiksCubeBuilder::fromDefaults()->build();
        Sticker::createForCubeAndDistance($cube, 5, []);
    }
}
