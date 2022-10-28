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

    public function testOpposite(): void
    {
        $turnCounterClockwise = Turn::fromMoveAndTurnTypeAndSlices(
            "R'",
            Move::R,
            TurnType::COUNTER_CLOCKWISE,
            1
        );
        $turnClockwise = Turn::fromMoveAndTurnTypeAndSlices(
            'R',
            Move::R,
            TurnType::CLOCKWISE,
            1
        );
        $doubleTurn = Turn::fromMoveAndTurnTypeAndSlices(
            'R',
            Move::R,
            TurnType::DOUBLE,
            1
        );
        $noTurn = Turn::fromMoveAndTurnTypeAndSlices(
            'R',
            Move::R,
            TurnType::NONE,
            1
        );

        $this->assertEquals($turnClockwise, $turnCounterClockwise->getOpposite());
        $this->assertEquals($turnCounterClockwise, $turnClockwise->getOpposite());
        $this->assertEquals($doubleTurn, $doubleTurn->getOpposite());
        $this->assertEquals($noTurn, $noTurn->getOpposite());
    }
}
