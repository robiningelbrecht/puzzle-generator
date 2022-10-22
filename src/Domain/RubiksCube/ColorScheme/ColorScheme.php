<?php

namespace App\Domain\RubiksCube\ColorScheme;

use App\Domain\Color;
use App\Domain\RubiksCube\Face;

class ColorScheme implements \JsonSerializable
{
    // Other colour schemes
    // Array('FFFF00', 'FF0000', '0000FF', 'FFFFFF', 'FF7F00', '00FF00'); // Basic
    // Array('EFEF00', 'C80000', '0000B6', 'F7F7F7', 'FFA100', '00B648'); // Cubestation
    // Array('EFFF01', 'FF0000', '1600FF', 'FEFFFC', 'FF8000', '047F01'); // cube.rider
    // Array('FEFE00', 'FE0000', '0000F2', 'FEFEFE', 'FE8600', '00F300'); // alg.garron
    private function __construct(
        private readonly Color $u,
        private readonly Color $r,
        private readonly Color $f,
        private readonly Color $d,
        private readonly Color $l,
        private readonly Color $b,
    ) {
        $colors = [
            $this->getColorForU(),
            $this->getColorForR(),
            $this->getColorForF(),
            $this->getColorForD(),
            $this->getColorForL(),
            $this->getColorForB(),
        ];
        if (count($colors) !== count(array_unique($colors))) {
            throw new \RuntimeException('Invalid ColorScheme provided, all colors have to be unique.');
        }
    }

    public static function fromColors(
        Color $u,
        Color $r,
        Color $f,
        Color $d,
        Color $l,
        Color $b,
    ): self {
        return new self($u, $r, $f, $d, $l, $b);
    }

    public function getColorForU(): Color
    {
        return $this->u;
    }

    public function getColorForR(): Color
    {
        return $this->r;
    }

    public function getColorForF(): Color
    {
        return $this->f;
    }

    public function getColorForD(): Color
    {
        return $this->d;
    }

    public function getColorForL(): Color
    {
        return $this->l;
    }

    public function getColorForB(): Color
    {
        return $this->b;
    }

    public function getColorForFace(Face $face): Color
    {
        return match ($face) {
            Face::U => $this->getColorForU(),
            Face::R => $this->getColorForR(),
            Face::F => $this->getColorForF(),
            Face::D => $this->getColorForD(),
            Face::L => $this->getColorForL(),
            Face::B => $this->getColorForB(),
        };
    }

    public function jsonSerialize(): array
    {
        return [
            'U' => $this->getColorForU(),
            'R' => $this->getColorForR(),
            'F' => $this->getColorForF(),
            'D' => $this->getColorForD(),
            'L' => $this->getColorForL(),
            'B' => $this->getColorForB(),
        ];
    }
}
