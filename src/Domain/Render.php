<?php

namespace App\Domain;

use App\Domain\RubiksCube\Face;
use App\Domain\RubiksCube\Render\FacePositions;
use App\Domain\RubiksCube\Render\Sticker;
use App\Domain\RubiksCube\RubiksCube;
use App\Domain\Svg\Svg;
use App\Domain\Svg\SvgSize;

class Render
{
    public static function cube(RubiksCube $cube, array $options = null): Svg
    {
        $svg = Svg::default($cube)
            ->withSize(SvgSize::fromOptionalInt($options['size'] ?? null))
            ->withBackgroundColor(Color::fromOptionalHexString($options['backgroundColor'] ?? null));

        $facePositions = FacePositions::default()
            ->rotate(...$cube->getRotations());

        $stickers = Sticker::createForCubeAndDistance($cube, 5);

        foreach (Face::cases() as $case) {
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
