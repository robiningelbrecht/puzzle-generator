<?php

namespace App\Tests\Unit\Infrastructure\ValueObject;

use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Point;
use App\Infrastructure\ValueObject\Position;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class PointTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerialize(): void
    {
        $point = Point::fromXY(100, 30);
        $this->assertMatchesJsonSnapshot(Json::encode($point));
    }

    public function testToString(): void
    {
        $point = Point::fromPosition(Position::fromXYZ(100, 30, 20));
        $this->assertEquals('100,30', (string) $point);
    }
}
