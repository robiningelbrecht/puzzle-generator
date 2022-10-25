<?php

namespace App\Domain\RubiksCube\ColorScheme;

use App\Infrastructure\ValueObject\Color;

class ColorSchemeBuilder
{
    private Color $u;
    private Color $r;
    private Color $f;
    private Color $d;
    private Color $l;
    private Color $b;

    private function __construct()
    {
        $this->u = Color::yellow();
        $this->r = Color::red();
        $this->f = Color::blue();
        $this->d = Color::white();
        $this->l = Color::orange();
        $this->b = Color::green();
    }

    public static function fromDefaults(): self
    {
        return new self();
    }

    public function build(): ColorScheme
    {
        return ColorScheme::fromColors(
            $this->u,
            $this->r,
            $this->f,
            $this->d,
            $this->l,
            $this->b
        );
    }

    public function withColorForU(Color $color = null): self
    {
        if (!$color) {
            return $this;
        }

        $this->u = $color;

        return $this;
    }

    public function withColorForR(Color $color = null): self
    {
        if (!$color) {
            return $this;
        }

        $this->r = $color;

        return $this;
    }

    public function withColorForF(Color $color = null): self
    {
        if (!$color) {
            return $this;
        }

        $this->f = $color;

        return $this;
    }

    public function withColorForD(Color $color = null): self
    {
        if (!$color) {
            return $this;
        }

        $this->d = $color;

        return $this;
    }

    public function withColorForL(Color $color = null): self
    {
        if (!$color) {
            return $this;
        }

        $this->l = $color;

        return $this;
    }

    public function withColorForB(Color $color = null): self
    {
        if (!$color) {
            return $this;
        }

        $this->b = $color;

        return $this;
    }
}
