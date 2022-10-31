<?php

namespace App\Domain\Svg;

use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\Face;
use App\Domain\RubiksCube\Render\FacePositions;
use App\Domain\RubiksCube\Render\Sticker;
use App\Domain\RubiksCube\Rotation;
use App\Domain\RubiksCube\RubiksCube;
use App\Domain\RubiksCube\View;
use App\Infrastructure\Math;
use App\Infrastructure\ValueObject\Color;
use App\Infrastructure\ValueObject\Point;
use App\Infrastructure\ValueObject\Position;

class SvgBuilder
{
    private const OUTLINE_WIDTH = 0.94;
    private const DISTANCE = 5;

    private Size $size;
    private Color $backgroundColor;
    private View $view;
    private array $rotations;

    private function __construct(
        private readonly RubiksCube $cube
    ) {
        $this->size = Size::fromInt(128);
        $this->backgroundColor = Color::transparent();
        $this->view = View::THREE_D;
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
        $cubeSize = $cube->getSize()->getValue();

        if (VIEW::NET === $this->view) {
            $elementWidth = 1 / $cubeSize;
            $halfElementWidth = $elementWidth / 2;

            $group = Group::fromAttributes(
                Attribute::fromNameAndValue('opacity', '1'),
                Attribute::fromNameAndValue('stroke', Color::black()),
                Attribute::fromNameAndValue('stroke-width', '0.02'),
                Attribute::fromNameAndValue('stroke-linejoin', 'round')
            );
            foreach ([Face::U, Face::R, Face::F, Face::D, Face::L, Face::B] as $face) {
                for ($i = 0; $i < $cubeSize; ++$i) {
                    $vOffset = -(-0.5 + $halfElementWidth + $elementWidth * $i);
                    for ($j = 0; $j < $cubeSize; ++$j) {
                        $hOffset = -0.5 + $halfElementWidth + $elementWidth * $j;

                        $translation = match ($face) {
                            Face::U => Position::fromXYZ(0, 1, 0),
                            Face::R => Position::fromXYZ(1, 0, 0),
                            Face::F => Position::fromXYZ(0, 0, 0),
                            Face::D => Position::fromXYZ(0, -1, 0),
                            Face::L => Position::fromXYZ(-1, 0, 0),
                            Face::B => Position::fromXYZ(2, 0, 0),
                        };

                        $p1 = Math::translate(Position::fromXYZ(-$halfElementWidth + $hOffset, $halfElementWidth + $vOffset, 0), $translation);
                        $p2 = Math::translate(Position::fromXYZ($halfElementWidth + $hOffset, $halfElementWidth + $vOffset, 0), $translation);
                        $p3 = Math::translate(Position::fromXYZ($halfElementWidth + $hOffset, -$halfElementWidth + $vOffset, 0), $translation);
                        $p4 = Math::translate(Position::fromXYZ(-$halfElementWidth + $hOffset, -$halfElementWidth + $vOffset, 0), $translation);

                        $group->addPolygon(Polygon::fromPointsAndFillColorAndStrokeColor(
                            [
                                Point::fromPosition(Math::scale(Math::translate($p1, Position::fromXYZ(-0.50, 0, 0)), 0.44)),
                                Point::fromPosition(Math::scale(Math::translate($p2, Position::fromXYZ(-0.50, 0, 0)), 0.44)),
                                Point::fromPosition(Math::scale(Math::translate($p3, Position::fromXYZ(-0.50, 0, 0)), 0.44)),
                                Point::fromPosition(Math::scale(Math::translate($p4, Position::fromXYZ(-0.50, 0, 0)), 0.44)),
                            ],
                            $cube->getFaces()[$face->value][($i * $cubeSize) + $j] ?? Color::black(),
                            $cube->getBaseColor()
                        ));
                    }
                }
            }

            return Svg::fromValues(
                $this->cube,
                $this->size,
                $this->backgroundColor,
                $this->view,
                $this->rotations,
                [$group],
                Face::cases(),
                [],
            );
        }
        if (View::TOP === $this->view) {
            // If the "top" view has to be rendered, we can ignore any given rotations.
            $this->rotations = [
                Rotation::fromAxisAndValue(Axis::X, -90),
            ];
        }

        $svgGroups = [];
        $facePositions = FacePositions::fromRotations(...$this->rotations);
        $stickers = Sticker::createForCubeAndDistance($cube, self::DISTANCE, $this->rotations);

        $cubeOutlineGroup = Group::fromAttributes(
            Attribute::fromNameAndValue('opacity', '1'),
            Attribute::fromNameAndValue('stroke', Color::black()),
            Attribute::fromNameAndValue('stroke-width', '0.1'),
            Attribute::fromNameAndValue('stroke-linejoin', 'round')
        );

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
            $svgGroups[] = $group;
        }

        if (View::TOP === $this->view) {
            $ollGroup = Group::fromAttributes(
                Attribute::fromNameAndValue('opacity', '1'),
                Attribute::fromNameAndValue('stroke-opacity', '1'),
                Attribute::fromNameAndValue('stroke-width', '0.02'),
                Attribute::fromNameAndValue('stroke-linejoin', 'round')
            );

            foreach ([Face::R, Face::F, Face::L, Face::B] as $face) {
                $faceStickers = $stickers[$face->value];
                $v1 = Math::scale($facePositions->getPositionForFace($face), 0);
                $v2 = Math::scale($facePositions->getPositionForFace($face), 0.2);

                for ($i = 0; $i < $cubeSize; ++$i) {
                    $center = Position::fromXYZ(
                        ($faceStickers[$i][0]->getPosition()->getX() + $faceStickers[$i + 1][1]->getPosition()->getX()) / 2,
                        ($faceStickers[$i][0]->getPosition()->getY() + $faceStickers[$i + 1][1]->getPosition()->getY()) / 2,
                        0,
                    );

                    /** @var Sticker $sticker */
                    $sticker = $faceStickers[0][$i];
                    $ollGroup->addPolygon(Polygon::fromPointsAndFillColorAndStrokeColor(
                        [
                            Point::fromPosition(Math::translate(Math::transScale($faceStickers[$i][0]->getPosition(), $center, 0.94), $v1)),
                            Point::fromPosition(Math::translate(Math::transScale($faceStickers[$i + 1][0]->getPosition(), $center, 0.94), $v1)),
                            Point::fromPosition(Math::translate(Math::transScale($faceStickers[$i + 1][1]->getPosition(), $center, 0.94), $v2)),
                            Point::fromPosition(Math::translate(Math::transScale($faceStickers[$i][1]->getPosition(), $center, 0.94), $v2)),
                        ],
                        $sticker->getColor(),
                        $cube->getBaseColor()
                    ));
                }
            }

            $svgGroups[] = $ollGroup;
        }

        return Svg::fromValues(
            $this->cube,
            $this->size,
            $this->backgroundColor,
            $this->view,
            $this->rotations,
            [$cubeOutlineGroup, ...$svgGroups],
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

    public function withView(View $view = null): self
    {
        if (!$view) {
            return $this;
        }

        $this->view = $view;

        return $this;
    }
}
