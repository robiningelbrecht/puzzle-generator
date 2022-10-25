<?php

namespace App\Domain\RubiksCube\Render;

use App\Domain\PuzzleException;
use App\Domain\RubiksCube\Face;
use App\Domain\RubiksCube\RubiksCube;
use App\Infrastructure\Math;
use App\Infrastructure\ValueObject\Color;
use App\Infrastructure\ValueObject\Point;
use App\Infrastructure\ValueObject\Position;

class Sticker
{
    private array $boundries;

    private function __construct(
        private readonly Position $position,
        private readonly Color $color
    ) {
    }

    public static function createForCubeAndDistance(RubiksCube $cube, int $distance, array $rotations = []): array
    {
        $stickers = [];
        $cubeSize = $cube->getSize()->getValue();

        foreach ($cube->getFaces() as $faceName => $faceColors) {
            $face = Face::from($faceName);
            for ($row = 0; $row <= $cubeSize; ++$row) {
                for ($col = 0; $col <= $cubeSize; ++$col) {
                    $position = match ($face) {
                        Face::U => Position::fromXYZ($row, 0, $cubeSize - $col),
                        Face::R => Position::fromXYZ($cubeSize, $col, $row),
                        Face::F => Position::fromXYZ($row, $col, 0),
                        Face::D => Position::fromXYZ($row, $cubeSize, $col),
                        Face::L => Position::fromXYZ(0, $col, $cubeSize - $row),
                        Face::B => Position::fromXYZ($cubeSize - $row, $col, $cubeSize)
                    };
                    // Now scale and transform point to ensure size/pos independent of dim
                    $centerTranslation = Position::fromXYZ(
                        -$cubeSize / 2,
                        -$cubeSize / 2,
                        -$cubeSize / 2
                    );
                    $position = Math::translate($position, $centerTranslation);
                    $position = Math::scale($position, 1 / $cubeSize);
                    // Rotate cube as per parameter settings.
                    foreach ($rotations as $rotation) {
                        $position = Math::rotate($position, $rotation->getAxis(), (Math::PI * $rotation->getValue()) / 180);
                    }

                    // Move cube away from viewer.
                    $position = Math::translate($position, Position::fromXYZ(0, 0, $distance));
                    // Finally project the 3D points onto 2D.
                    $position = Math::project($position, $distance);
                    $stickers[$face->value][$row][$col] = new self(
                        $position,
                        $faceColors[($row * $cubeSize) + $col] ?? Color::black(),
                    );
                }
            }
        }

        // Scale points in towards centre.
        foreach (Face::cases() as $face) {
            for ($row = 0; $row < $cubeSize; ++$row) {
                for ($col = 0; $col < $cubeSize; ++$col) {
                    $centerPoint = Position::fromXYZ(
                        ($stickers[$face->value][$col][$row]->getPosition()->getX() + $stickers[$face->value][$col + 1][$row + 1]->getPosition()->getX()) / 2,
                        ($stickers[$face->value][$col][$row]->getPosition()->getY() + $stickers[$face->value][$col + 1][$row + 1]->getPosition()->getY()) / 2,
                        0
                    );

                    /** @var \App\Domain\RubiksCube\Render\Sticker $sticker */
                    $sticker = $stickers[$face->value][$row][$col];
                    $sticker->addBoundries(
                        Point::fromPosition(Math::transScale($stickers[$face->value][$col][$row]->getPosition(), $centerPoint, 0.85)),
                        Point::fromPosition(Math::transScale($stickers[$face->value][$col + 1][$row]->getPosition(), $centerPoint, 0.85)),
                        Point::fromPosition(Math::transScale($stickers[$face->value][$col + 1][$row + 1]->getPosition(), $centerPoint, 0.85)),
                        Point::fromPosition(Math::transScale($stickers[$face->value][$col][$row + 1]->getPosition(), $centerPoint, 0.85)),
                    );
                }
            }
        }

        return $stickers;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function getBoundries(): array
    {
        return $this->boundries;
    }

    private function addBoundries(Point ...$boundries): void
    {
        if (4 != count($boundries)) {
            throw new PuzzleException('Invalid number of boundries provided');
        }
        $this->boundries = $boundries;
    }
}
