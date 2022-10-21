<?php

namespace App\RubiksCube\Turn;

use App\RubiksCube\Move;

class Turn
{
    private function __construct(
        private readonly Move $move,
        private readonly TurnType $turnType,
        private readonly int $slices,
    ) {
    }

    public static function fromMoveAndTurnTypeAndSlices(
        Move $move,
        TurnType $turnType,
        int $slices,
    ): self {
        return new self($move, $turnType, $slices);
    }

    public function getMove(): Move
    {
        return $this->move;
    }

    public function getTurnType(): TurnType
    {
        return $this->turnType;
    }

    public function getSlices(): int
    {
        return $this->slices;
    }
}
