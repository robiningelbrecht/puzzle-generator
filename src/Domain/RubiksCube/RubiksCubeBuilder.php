<?php

namespace App\Domain\RubiksCube;

use App\Domain\Color;
use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\ColorScheme\ColorScheme;

class RubiksCubeBuilder
{
    private CubeSize $size;
    private array $rotations;
    private ColorScheme $colorScheme;
    private Color $baseColor;
    private ?Mask $mask;

    private function __construct()
    {
        $this->size = CubeSize::fromInt(3);
        $this->rotations = [
            Rotation::fromAxisAndValue(Axis::Y, Rotation::DEFAULT_Y),
            Rotation::fromAxisAndValue(Axis::X, Rotation::DEFAULT_X),
        ];

        $this->colorScheme = ColorScheme::fromColors(
            Color::yellow(),
            Color::red(),
            Color::blue(),
            Color::white(),
            Color::orange(),
            Color::green(),
        );
        $this->baseColor = Color::black();
        $this->mask = null;
    }

    public static function fromDefaults(): self
    {
        return new self();
    }

    public function build(): RubiksCube
    {
        return RubiksCube::fromValues(
            $this->size,
            $this->rotations,
            $this->colorScheme,
            $this->baseColor,
            $this->mask
        );
    }

    public function withSize(CubeSize $size = null): self
    {
        if (!$size) {
            return $this;
        }

        $this->size = $size;

        return $this;
    }

    public function withRotations(Rotation ...$rotations): self
    {
        if (empty($rotations)) {
            return $this;
        }
        $this->rotations = $rotations;

        return $this;
    }

    public function withColorScheme(ColorScheme $colorScheme): self
    {
        $this->colorScheme = $colorScheme;

        return $this;
    }

    public function withBaseColor(Color $color = null): self
    {
        if (!$color) {
            return $this;
        }

        $this->baseColor = $color;

        return $this;
    }

    public function withMask(Mask $mask = null): self
    {
        $this->mask = $mask;

        return $this;
    }
}
