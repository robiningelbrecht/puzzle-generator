<?php

namespace App\RubiksCube\Turn;

enum TurnType: string
{
    case CLOCKWISE = 'clockwise';
    case COUNTER_CLOCKWISE = 'counterClockwise';
    case DOUBLE = 'double';
    case NONE = 'none';

    public function getOpposite(): self
    {
        return match ($this) {
            self::CLOCKWISE => self::COUNTER_CLOCKWISE,
            self::COUNTER_CLOCKWISE => self::CLOCKWISE,
            default => $this,
        };
    }
}
