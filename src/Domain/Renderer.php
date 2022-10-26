<?php

namespace App\Domain;

use App\Domain\RubiksCube\Render\FacePositions;
use App\Domain\RubiksCube\Render\Sticker;
use App\Domain\RubiksCube\RubiksCube;
use App\Domain\Svg\Attribute;
use App\Domain\Svg\Group;
use App\Domain\Svg\Polygon;
use App\Domain\Svg\Size;
use App\Domain\Svg\Svg;
use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Color;
use App\Infrastructure\ValueObject\Point;

class Renderer
{
    private const OUTLINE_WIDTH = 0.94;

    public static function renderCube(
        RubiksCube $cube,
        array $rotations = [],
        Size $svgSize = null,
        Color $backgroundColor = null): Svg
    {
        $svg = Svg::default($cube)
            ->withSize($svgSize)
            ->withBackgroundColor($backgroundColor)
            ->withRotations(...$rotations); // This is only added on the SVG VO, so we can output it in the JSON notation.

        $facePositions = FacePositions::fromRotations(...$svg->getRotations());
        // These are only added on the SVG VO, so we can output it in the JSON notation for debugging reasons.
        $svg
            ->withVisibleFaces(...$facePositions->getVisibleFaces())
            ->withHiddenFaces(...$facePositions->getHiddenFaces());
        $stickers = Sticker::createForCubeAndDistance($cube, 5, $svg->getRotations());

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

        $svg->withGroups($cubeOutlineGroup, ...$faceStickerGroups);

        return $svg;
    }
}
