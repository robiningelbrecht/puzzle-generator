<?php

namespace App\Domain\Svg;

class Group implements \JsonSerializable
{
    private array $polygons;

    private function __construct(
        private readonly array $attributes,
    ) {
        $this->polygons = [];
    }

    public static function fromAttributes(Attribute ...$attributes): self
    {
        return new self($attributes);
    }

    public function addPolygon(Polygon $polygon): self
    {
        $this->polygons[] = $polygon;

        return $this;
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
