<?php

namespace App\Domain;

use App\Domain\RubiksCube\Render\FacePositions;
use App\Domain\RubiksCube\Render\Sticker;
use App\Domain\RubiksCube\RubiksCube;
use App\Domain\Svg\Svg;
use App\Domain\Svg\SvgSize;
use App\Infrastructure\ValueObject\Color;
use App\Infrastructure\ValueObject\Point;

class Renderer
{
    private const OUTLINE_WIDTH = 0.94;

    public static function renderCube(
        RubiksCube $cube,
        array $rotations = [],
        SvgSize $svgSize = null,
        Color $backgroundColor = null): Svg
    {
        $svg = Svg::default($cube)
            ->withSize($svgSize)
            ->withBackgroundColor($backgroundColor)
            ->withRotations(...$rotations); // This is only added on the SVG VO, so we can output it in the JSON notation.

        $facePositions = FacePositions::fromRotations(...$rotations);
        $stickers = Sticker::createForCubeAndDistance($cube, 5, $rotations);

        $cubeSize = $cube->getSize()->getValue();
        foreach ($facePositions->getVisibleFaces() as $face) {
            $faceStickers = $stickers[$face->value];
            // Calculate face outline points.
            $faceOutlinePoints = [
                Point::fromXY($faceStickers[0][0]->getPosition()->getX() * self::OUTLINE_WIDTH, $faceStickers[0][0]->getPosition()->getY() * self::OUTLINE_WIDTH),
                Point::fromXY($faceStickers[$cubeSize][0]->getPosition()->getX() * self::OUTLINE_WIDTH, $faceStickers[$cubeSize][0]->getPosition()->getY() * self::OUTLINE_WIDTH),
                Point::fromXY($faceStickers[$cubeSize][$cubeSize]->getPosition()->getX() * self::OUTLINE_WIDTH, $faceStickers[$cubeSize][$cubeSize]->getPosition()->getY() * self::OUTLINE_WIDTH),
                Point::fromXY($faceStickers[0][$cubeSize]->getPosition()->getX() * self::OUTLINE_WIDTH, $faceStickers[0][$cubeSize]->getPosition()->getY() * self::OUTLINE_WIDTH),
            ];
        }

        return $svg;
    }
}
