<?php

namespace App\Domain\RubiksCube\Rotation;

class Rotation implements \JsonSerializable
{
    public const DEFAULT_X = 43;
    public const DEFAULT_Y = 35;
    public const DEFAULT_Z = 29;

    private function __construct(
        private readonly int $x,
        private readonly int $y,
        private readonly int $z
    ) {
        if ($x < -360 || $x > 360) {
            throw new \RuntimeException(sprintf('Invalid number (%s) of degrees for X provided', $this->x));
        }
        if ($y < -360 || $y > 360) {
            throw new \RuntimeException(sprintf('Invalid number (%s) of degrees for Y provided', $this->y));
        }
        if ($z < -360 || $z > 360) {
            throw new \RuntimeException(sprintf('Invalid number (%s) of degrees for Z provided', $this->z));
        }
    }

    public static function fromXYZ(
        int $x,
        int $y,
        int $z,
    ): self {
        return new self($x, $y, $z);
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getZ(): int
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
