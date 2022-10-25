<?php

namespace App\Domain\RubiksCube;

use App\Domain\RubiksCube\ColorScheme\ColorScheme;
use App\Infrastructure\ValueObject\Color;

class RubiksCubeBuilder
{
    private Size $size;
    private ColorScheme $colorScheme;
    private Color $baseColor;

    private function __construct()
    {
        $this->size = Size::fromInt(3);
        $this->colorScheme = ColorScheme::fromColors(
            Color::yellow(),
            Color::red(),
            Color::blue(),
            Color::white(),
            Color::orange(),
            Color::green(),
        );
        $this->baseColor = Color::black();
    }

    public static function fromDefaults(): self
    {
        return new self();
    }

    public function build(): RubiksCube
    {
        return RubiksCube::fromValues(
            $this->size,
            $this->colorScheme,
            $this->baseColor,
        );
    }

    public function withSize(Size $size = null): self
    {
        if (!$size) {
            return $this;
        }

        $this->size = $size;

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
}
