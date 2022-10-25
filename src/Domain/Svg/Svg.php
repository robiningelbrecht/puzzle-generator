<?php

namespace App\Domain\Svg;

use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\Rotation;
use App\Domain\RubiksCube\RubiksCube;
use App\Infrastructure\ValueObject\Color;

class Svg implements \JsonSerializable
{
    private function __construct(
        private readonly RubiksCube $cube,
        private SvgSize $size,
        private Color $backgroundColor,
        private array $rotations,
    ) {
    }

    public static function default(RubiksCube $cube): self
    {
        return new self(
            $cube,
            SvgSize::fromInt(128),
            Color::transparent(),
            [
                Rotation::fromAxisAndValue(Axis::Y, Rotation::DEFAULT_Y),
                Rotation::fromAxisAndValue(Axis::X, Rotation::DEFAULT_X),
            ]
        );
    }

    public function getCube(): RubiksCube
    {
        return $this->cube;
    }

    public function getSize(): SvgSize
    {
        return $this->size;
    }

    public function withSize(SvgSize $size = null): self
    {
        if (!$size) {
            return $this;
        }

        $this->size = $size;

        return $this;
    }

    public function getBackgroundColor(): Color
    {
        return $this->backgroundColor;
    }

    public function withBackgroundColor(Color $color = null): self
    {
        if (!$color) {
            return $this;
        }

        $this->backgroundColor = $color;

        return $this;
    }

    public function getRotations(): array
    {
        return $this->rotations;
    }

    public function withRotations(Rotation ...$rotations): self
    {
        if (!$rotations) {
            return $this;
        }

        $this->rotations = $rotations;

        return $this;
    }

    public function getViewBox(): Viewbox
    {
        return Viewbox::fromDefaults();
    }

    public function jsonSerialize(): array
    {
        return [
            'cube' => $this->getCube(),
            'size' => $this->getSize(),
            'rotations' => $this->getRotations(),
            'backgroundColor' => $this->getBackgroundColor(),
            'viewbox' => $this->getViewBox(),
        ];
    }
}
