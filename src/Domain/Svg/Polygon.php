<?php

namespace App\Domain\Svg;

use App\Infrastructure\ValueObject\Color;

class Polygon implements \JsonSerializable
{
    private function __construct(
        private readonly array $points,
        private readonly Color $fillColor,
        private readonly Color $strokeColor,
    ) {
    }

    public function getPoints(): array
    {
        return $this->points;
    }

    public function getFillColor(): Color
    {
        return $this->fillColor;
    }

    public function getStrokeColor(): Color
    {
        return $this->strokeColor;
    }

    public function jsonSerialize(): array
    {
        return [
            'points' => $this->getPoints(),
            'fill' => $this->getFillColor(),
            'stroke' => $this->getStrokeColor(),
        ];
    }
}
