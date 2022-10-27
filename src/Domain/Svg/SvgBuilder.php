<?php

namespace App\Domain\Svg;

use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\Face;
use App\Domain\RubiksCube\Render\FacePositions;
use App\Domain\RubiksCube\Render\Sticker;
use App\Domain\RubiksCube\Rotation;
use App\Domain\RubiksCube\RubiksCube;
use App\Infrastructure\ValueObject\Color;
use App\Infrastructure\ValueObject\Point;

class SvgBuilder
{
    private const OUTLINE_WIDTH = 0.94;
    private const DEPTH = 5;

    private Size $size;
    private Color $backgroundColor;
    private array $rotations;

    private function __construct(
        private readonly RubiksCube $cube
    ) {
        $this->size = Size::fromInt(128);
        $this->backgroundColor = Color::transparent();
        $this->rotations = [
            Rotation::fromAxisAndValue(Axis::Y, Rotation::DEFAULT_Y),
            Rotation::fromAxisAndValue(Axis::X, Rotation::DEFAULT_X),
        ];
    }

    public static function forCube(RubiksCube $cube): self
    {
        return new self($cube);
    }

    public function build(): Svg
    {
        $cube = $this->cube;
        $facePositions = FacePositions::fromRotations(...$this->rotations);
        $stickers = Sticker::createForCubeAndDistance($cube, self::DEPTH, $this->rotations);

        $cubeSize = $cube->getSize()->getValue();
        $cubeOutlineGroup = Group::fromAttributes(
            Attribute::fromNameAndValue('opacity', '1'),
            Attribute::fromNameAndValue('stroke', Color::black()),
            Attribute::fromNameAndValue('stroke-width', '0.1'),
            Attribute::fromNameAndValue('stroke-linejoin', 'round')
        );
        $faceStickerGroups = [];
        foreach ($facePositions->getVisibleFaces() as $face) {
            $faceStickers = $stickers[$face->value];
            // Calculate face outline points.
            $cubeOutlineGroup->addPolygon(Polygon::fromPointsAndFillColorAndStrokeColor(
                [
                    Point::fromXY($faceStickers[0][0]->getPosition()->getX() * self::OUTLINE_WIDTH, $faceStickers[0][0]->getPosition()->getY() * self::OUTLINE_WIDTH),
                    Point::fromXY($faceStickers[$cubeSize][0]->getPosition()->getX() * self::OUTLINE_WIDTH, $faceStickers[$cubeSize][0]->getPosition()->getY() * self::OUTLINE_WIDTH),
                    Point::fromXY($faceStickers[$cubeSize][$cubeSize]->getPosition()->getX() * self::OUTLINE_WIDTH, $faceStickers[$cubeSize][$cubeSize]->getPosition()->getY() * self::OUTLINE_WIDTH),
                    Point::fromXY($faceStickers[0][$cubeSize]->getPosition()->getX() * self::OUTLINE_WIDTH, $faceStickers[0][$cubeSize]->getPosition()->getY() * self::OUTLINE_WIDTH),
                ],
                $cube->getBaseColor(),
                $cube->getBaseColor(),
            ));

            // Add a group with the face stickers.
            $group = Group::fromAttributes(
                Attribute::fromNameAndValue('stoke-opacity', '0.5'),
                Attribute::fromNameAndValue('stroke-width', '0'),
                Attribute::fromNameAndValue('stroke-linejoin', 'round')
            );

            for ($row = 0; $row < $cubeSize; ++$row) {
                for ($col = 0; $col < $cubeSize; ++$col) {
                    /** @var Sticker $sticker */
                    $sticker = $faceStickers[$row][$col];
                    $group->addPolygon(Polygon::fromPointsAndFillColorAndStrokeColor(
                        $sticker->getBoundries(),
                        $sticker->getColor(),
                        $cube->getBaseColor()
                    ));
                }
            }
            $faceStickerGroups[] = $group;
        }

        return Svg::fromValues(
            $this->cube,
            $this->size,
            $this->backgroundColor,
            $this->rotations,
            [$cubeOutlineGroup, ...$faceStickerGroups],
            $facePositions->getVisibleFaces(),
            $facePositions->getHiddenFaces(),
        );
    }

    public function withSize(Size $size = null): self
    {
        if (!$size) {
            return $this;
        }

        $this->size = $size;

        return $this;
    }

    public function withBackgroundColor(Color $color = null): self
    {
        if (!$color) {
            return $this;
        }

        $this->backgroundColor = $color;

        return $this;
    }

    public function withRotations(Rotation ...$rotations): self
    {
        if (!$rotations) {
            return $this;
        }

        $this->rotations = $rotations;

        return $this;
    }
}
