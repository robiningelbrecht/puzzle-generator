<?php

namespace App\Domain\Svg;

class Group implements \JsonSerializable
{
    private function __construct(
        private readonly array $attributes,
        private readonly array $polygons
    ) {
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getPolygons(): array
    {
        return $this->polygons;
    }

    public function jsonSerialize(): array
    {
        return [
            'attributes' => $this->getAttributes(),
            'polygons' => $this->getPolygons(),
        ];
    }
}
