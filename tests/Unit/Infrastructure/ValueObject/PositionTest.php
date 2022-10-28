<?php

namespace App\Tests\Unit\Infrastructure\ValueObject;

use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Position;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class PositionTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerialize(): void
    {
        $position = Position::fromXYZ(100, 30, 21);
        $this->assertMatchesJsonSnapshot(Json::encode($position));
    }
}
