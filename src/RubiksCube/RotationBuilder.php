<?php

namespace App\RubiksCube;

class RotationBuilder
{
    private int $x;
    private int $y;
    private int $z;

    private function __construct()
    {
        $this->x = Rotation::DEFAULT_X;
        $this->y = Rotation::DEFAULT_Y;
        $this->z = Rotation::DEFAULT_Z;
    }

    public static function fromDefaults(): self
    {
        return new self();
    }

    public function build(): Rotation
    {
        return Rotation::fromXYZ(
            $this->x,
            $this->y,
            $this->z
        );
    }

    public function withX(int $x = null): self
    {
        if (is_null($x)) {
            return $this;
        }
        $this->x = $x;

        return $this;
    }

    public function withY(int $y = null): self
    {
        if (is_null($y)) {
            return $this;
        }
        $this->y = $y;

        return $this;
    }

    public function withZ(int $z = null): self
    {
        if (is_null($z)) {
            return $this;
        }
        $this->z = $z;

        return $this;
    }
}