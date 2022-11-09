<?php

namespace App\Tests\Unit\Domain\RubiksCube;

use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\Rotation;
use App\Infrastructure\Exception\PuzzleException;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class RotationTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerialize(): void
    {
        $this->assertMatchesJsonSnapshot(Json::encode(Rotation::fromAxisAndValue(Axis::Y, 30)));
    }

    public function testFromMap(): void
    {
        $this->assertMatchesJsonSnapshot(Json::encode(Rotation::fromMap([
            ['axis' => 'x', 'value' => 30],
            ['axis' => 'y', 'value' => 231],
            ['axis' => 'z', 'value' => -90],
        ])));
    }

    public function testItShouldThrowOnInvalidValue(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid rotation degree <strong>361</strong> provided.');

        Rotation::fromAxisAndValue(Axis::Y, 361);
    }

    public function testItShouldThrowOnInvalidMap(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid rotation provided, <strong>axis</strong> and <strong>value</strong> are required');

        Rotation::fromMap([['axis' => 'x', 'john' => 'doe']]);
    }

    public function testItShouldThrowOnInvalidMapCase2(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid rotation provided, <strong>axis</strong> and <strong>value</strong> are required');

        Rotation::fromMap([['john' => 'doe', 'value' => 3]]);
    }

    public function testItShouldThrowOnInvalidMapCase3(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid axis <strong>doe</strong> provided, valid values are <code>x</code>, <code>y</code>, <code>z</code>');

        Rotation::fromMap([['axis' => 'doe', 'value' => 3]]);
    }
}
