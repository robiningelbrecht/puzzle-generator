<?php

namespace App\Domain\Svg;

use App\Domain\RubiksCube\RubiksCube;
use App\Infrastructure\ValueObject\Color;

class Svg implements \JsonSerializable
{
    private function __construct(
        private readonly RubiksCube $cube,
        private readonly Size $size,
        private readonly Color $backgroundColor,
        private readonly array $rotations,
        private readonly array $groups,
        private readonly array $visibleFaces,
        private readonly array $hiddenFaces,
    ) {
    }

    public static function fromValues(
        RubiksCube $cube,
        Size $size,
        Color $backgroundColor,
        array $rotations,
        array $groups,
        array $visibleFaces,
        array $hiddenFaces,
    ): self {
        return new self(
            $cube,
            $size,
            $backgroundColor,
            $rotations,
            $groups,
            $visibleFaces,
            $hiddenFaces,
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

    public function getBackgroundColor(): Color
    {
        return $this->backgroundColor;
    }

    public function getRotations(): array
    {
        return $this->rotations;
    }

    public function getVisibleFaces(): array
    {
        return $this->visibleFaces;
    }

    public function getHiddenFaces(): array
    {
        return $this->hiddenFaces;
    }

    public function getGroups(): array
    {
        return $this->groups;
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
