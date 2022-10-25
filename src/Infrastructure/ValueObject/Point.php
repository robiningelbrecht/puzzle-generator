<?php

namespace App\Infrastructure\ValueObject;

class Point
{
    private function __construct(
        private readonly float $x,
        private readonly float $y,
    ) {
    }

    public static function fromXY(
        float $x,
        float $y,
    ): self {
        return new self($x, $y);
    }

    public static function fromPosition(Position $position): self
    {
        return self::fromXY(
            $position->getX(),
            $position->getY()
        );
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }
}
