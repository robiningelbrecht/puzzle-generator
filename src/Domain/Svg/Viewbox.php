<?php

namespace App\Domain\Svg;

class Viewbox implements \JsonSerializable
{
    private function __construct(
        private readonly float $x,
        private readonly float $y,
        private readonly float $width,
        private readonly float $height,
    ) {
    }

    public static function fromDefaults(): self
    {
        return new self(-0.9, -0.9, 1.8, 1.8);
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function jsonSerialize(): array
    {
        return [
            'x' => $this->getX(),
            'y' => $this->getY(),
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
        ];
    }
}
