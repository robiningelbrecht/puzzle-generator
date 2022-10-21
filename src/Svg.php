<?php

namespace App;

use App\RubiksCube\RubiksCube;

class Svg implements \JsonSerializable
{
    private function __construct(
        private RubiksCube $cube,
        private SvgSize $size,
        private Color $backgroundColor,
    ) {
    }

    public static function default(RubiksCube $cube): self
    {
        return new self(
            $cube,
            SvgSize::fromInt(128),
            Color::white(),
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

    /**
     * @return array{'cube': \App\RubiksCube\RubiksCube, 'size': \App\SvgSize, 'backgroundColor': Color}
     */
    public function jsonSerialize(): array
    {
        return [
            'cube' => $this->getCube(),
            'size' => $this->getSize(),
            'backgroundColor' => $this->getBackgroundColor(),
        ];
    }
}
