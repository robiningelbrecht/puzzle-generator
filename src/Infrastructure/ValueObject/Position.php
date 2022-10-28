<?php

namespace App\Infrastructure\ValueObject;

class Position implements \JsonSerializable
{
    private function __construct(
        private readonly float $x,
        private readonly float $y,
        private readonly float $z
    ) {
    }

    public static function fromXYZ(
        float $x,
        float $y,
        float $z,
    ): self {
        return new self($x, $y, $z);
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getZ(): float
    {
        return $this->z;
    }

    public function jsonSerialize(): array
    {
        return [
            'x' => $this->getX(),
            'y' => $this->getY(),
            'z' => $this->getZ(),
        ];
    }
}
