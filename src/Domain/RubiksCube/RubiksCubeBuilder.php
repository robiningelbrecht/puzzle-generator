<?php

namespace App\Domain\RubiksCube;

use App\Domain\Color;
use App\Domain\RubiksCube\ColorScheme\ColorScheme;
use App\Domain\RubiksCube\Rotation\Rotation;

class RubiksCubeBuilder
{
    private CubeSize $size;
    private Rotation $rotation;
    private ColorScheme $colorScheme;
    private Color $baseColor;
    private ?Mask $mask;

    private function __construct()
    {
        $this->size = CubeSize::fromInt(3);
        $this->rotation = Rotation::fromXYZ(
            Rotation::DEFAULT_X,
            Rotation::DEFAULT_Y,
            Rotation::DEFAULT_Z,
        );

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
            $this->rotation,
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

    public function withRotation(Rotation $rotation): self
    {
        $this->rotation = $rotation;

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
