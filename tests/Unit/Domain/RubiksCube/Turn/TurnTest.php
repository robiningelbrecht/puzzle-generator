<?php

namespace App\Tests\Unit\Domain\RubiksCube\Turn;

use App\Domain\RubiksCube\Move;
use App\Domain\RubiksCube\Turn\Turn;
use App\Domain\RubiksCube\Turn\TurnType;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class TurnTest extends TestCase
{
    use MatchesSnapshots;

    public function testJsonSerialize(): void
    {
        $this->assertMatchesJsonSnapshot(Json::encode(
            Turn::fromMoveAndTurnTypeAndSlices('3B', Move::B, TurnType::CLOCKWISE, 3)
        ));
    }
}
