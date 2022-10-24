<?php

namespace App\Tests\Unit\Domain\RubiksCube;

use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\Rotation;
use App\Infrastructure\Json;
use App\Infrastructure\PuzzleException;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class RotationTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerialize(): void
    {
        $this->assertMatchesJsonSnapshot(Json::encode(Rotation::fromAxisAndValue(Axis::Y, 30)));
    }

    public function testItShouldThrowOnInvalidValue(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid number (361) of rotation degrees provided');

        Rotation::fromAxisAndValue(Axis::Y, 361);
    }
}
