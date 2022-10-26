<?php

namespace App\Domain\Svg;

use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\Face;
use App\Domain\RubiksCube\Rotation;
use App\Domain\RubiksCube\RubiksCube;
use App\Infrastructure\ValueObject\Color;

class Svg implements \JsonSerializable
{
    private array $groups;
    private array $visibleFaces;
    private array $hiddenFaces;

    private function __construct(
        private readonly RubiksCube $cube,
        private Size $size,
        private Color $backgroundColor,
        private array $rotations,
    ) {
        $this->groups = [];
        $this->visibleFaces = [];
        $this->hiddenFaces = [];
    }

    public static function default(RubiksCube $cube): self
    {
        return new self(
            $cube,
            Size::fromInt(128),
            Color::transparent(),
            [
                Rotation::fromAxisAndValue(Axis::Y, Rotation::DEFAULT_Y),
                Rotation::fromAxisAndValue(Axis::X, Rotation::DEFAULT_X),
            ],
        );
    }

    public function getCube(): RubiksCube
    {
        return $this->cube;
    }

    public function getSize(): Size
    {
        return $this->size;
    }

    public function withSize(Size $size = null): self
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

    public function getVisibleFaces(): array
    {
        return $this->visibleFaces;
    }

    public function withVisibleFaces(Face ...$visibleFaces): self
    {
        $this->visibleFaces = $visibleFaces;

        return $this;
    }

    public function getHiddenFaces(): array
    {
        return $this->hiddenFaces;
    }

    public function withHiddenFaces(Face ...$hiddenFaces): self
    {
        $this->hiddenFaces = $hiddenFaces;

        return $this;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function withGroups(Group ...$groups): self
    {
        $this->groups = $groups;

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
            'svg' => [
                'size' => $this->getSize(),
                'rotations' => $this->getRotations(),
                'backgroundColor' => $this->getBackgroundColor(),
                'viewbox' => $this->getViewBox(),
                'faces' => [
                    'visible' => $this->getVisibleFaces(),
                    'hidden' => $this->getHiddenFaces(),
                ],
                'groups' => $this->getGroups(),
            ],
        ];
    }
}
