<?php

namespace App\Domain;

use App\Domain\RubiksCube\Render\FacePositions;
use App\Domain\RubiksCube\Render\Sticker;
use App\Domain\RubiksCube\RubiksCube;
use App\Domain\Svg\Svg;
use App\Domain\Svg\SvgSize;

class Render
{
    public static function cube(
        RubiksCube $cube,
        array $rotations = [],
        SvgSize $svgSize = null,
        Color $backgroundColor = null): Svg
    {
        $svg = Svg::default($cube)
            ->withSize($svgSize)
            ->withBackgroundColor($backgroundColor)
            ->withRotations(...$rotations); // This is only added on the SVG VO so we can output it in the JSON notation.

        $facePositions = FacePositions::default()
            ->rotate(...$rotations);

        $stickers = Sticker::createForCubeAndDistance($cube, 5, $rotations);

        foreach ($facePositions->getVisibleFaces() as $case) {
            /*
             * const width =  outlineWidth: 0.94,
             *  $outlinePoints = [
            [face[0][0][0] * width, face[0][0][1] * width],
            [face[cubeSize][0][0] * width, face[cubeSize][0][1] * width],
            [face[cubeSize][cubeSize][0] * width, face[cubeSize][cubeSize][1] * width],
            [face[0][cubeSize][0] * width, face[0][cubeSize][1] * width],
            ];
             */
        }

        return $svg;
    }
}
