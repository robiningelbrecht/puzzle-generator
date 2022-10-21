<?php

namespace App\RubiksCube\Rotation;

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
            throw new \RuntimeException('Invalid number of degrees for X provided');
        }
        if ($y < -360 || $y > 360) {
            throw new \RuntimeException('Invalid number of degrees for Y provided');
        }
        if ($z < -360 || $z > 360) {
            throw new \RuntimeException('Invalid number of degrees for Z provided');
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

    public function withY(int $y = null): self
    {
        if (is_null($y)) {
            return $this;
        }

        return new self(
            $this->getX(),
            $y,
            $this->getZ(),
        );
    }

    public function getZ(): int
    {
        return $this->z;
    }

    public function withZ(int $z = null): self
    {
        if (is_null($z)) {
            return $this;
        }

        return new self(
            $this->getX(),
            $this->getY(),
            $z,
        );
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
