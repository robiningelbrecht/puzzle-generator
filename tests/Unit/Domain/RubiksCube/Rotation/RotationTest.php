<?php

namespace App\Tests\Unit\Domain\RubiksCube\Rotation;

use App\Domain\RubiksCube\Rotation\RotationBuilder;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class RotationTest extends TestCase
{
    use MatchesSnapshots;

    public function testWithNullValues(): void
    {
        $rotation = RotationBuilder::fromDefaults()
            ->withX(null)
            ->withY(null)
            ->withZ(null)
            ->build();

        $this->assertEquals($rotation, RotationBuilder::fromDefaults()->build());
        $this->assertMatchesJsonSnapshot(Json::encode($rotation));
    }

    public function testWithAllValues(): void
    {
        $rotation = RotationBuilder::fromDefaults()
            ->withX(100)
            ->withY(105)
            ->withZ(30)
            ->build();

        $this->assertMatchesJsonSnapshot(Json::encode($rotation));
    }

    public function testItShouldThrowOnInvalidValueForX(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid number (400) of degrees for X provided');

        RotationBuilder::fromDefaults()
            ->withX(400)
            ->withY(105)
            ->withZ(30)
            ->build();
    }

    public function testItShouldThrowOnInvalidValueForY(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid number (400) of degrees for Y provided');

        RotationBuilder::fromDefaults()
            ->withX(105)
            ->withY(400)
            ->withZ(30)
            ->build();
    }

    public function testItShouldThrowOnInvalidValueFor2(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid number (361) of degrees for Z provided');

        RotationBuilder::fromDefaults()
            ->withX(89)
            ->withY(105)
            ->withZ(361)
            ->build();
    }
}
