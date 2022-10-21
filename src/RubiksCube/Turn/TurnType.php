<?php

namespace App\RubiksCube\Turn;

enum TurnType
{
    case CLOCKWISE;
    case COUNTER_CLOCKWISE;
    case DOUBLE;
    case NONE;

    public function getOpposite(): self
    {
        return match ($this) {
            self::CLOCKWISE => self::COUNTER_CLOCKWISE,
            self::COUNTER_CLOCKWISE => self::CLOCKWISE,
            default => $this,
        };
    }
}
