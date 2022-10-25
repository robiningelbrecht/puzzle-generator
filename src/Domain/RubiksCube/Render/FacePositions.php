<?php

namespace App\Domain\RubiksCube\Render;

use App\Domain\RubiksCube\Face;
use App\Domain\RubiksCube\Rotation;
use App\Infrastructure\Math;
use App\Infrastructure\ValueObject\Position;

class FacePositions
{
    private array $values;

    private function __construct(
        array $rotations
    ) {
        $this->values = [
            Face::U->value => Position::fromXYZ(0, -1, 0),
            Face::R->value => Position::fromXYZ(1, 0, 0),
            Face::F->value => Position::fromXYZ(0, 0, -1),
            Face::D->value => Position::fromXYZ(0, 1, 0),
            Face::L->value => Position::fromXYZ(-1, 0, 0),
            Face::B->value => Position::fromXYZ(0, 0, 1),
        ];

        foreach (Face::cases() as $face) {
            foreach ($rotations as $rotation) {
                $this->values[$face->value] = Math::rotate($this->values[$face->value], $rotation->getAxis(), (Math::PI * $rotation->getValue()) / 180);
            }
        }
    }

    public static function fromRotations(Rotation ...$rotations): self
    {
        return new self($rotations);
    }

    public function getHiddenFaces(): array
    {
        return array_filter($this->getRenderOrder(), fn (Face $face) => !$this->isFaceVisible($face));
    }

    public function getVisibleFaces(): array
    {
        return array_filter($this->getRenderOrder(), fn (Face $face) => $this->isFaceVisible($face));
    }

    private function isFaceVisible(Face $face): bool
    {
        return $this->values[$face->value]->getZ() < -0.105;
    }

    private function getRenderOrder(): array
    {
        // Determines face render order based on z position. Faces further away
        // will render first so anything closer will be drawn on top.
        $faces = Face::cases();
        $values = $this->values;
        usort($faces, function (Face $a, Face $b) use ($values) {
            if ($values[$b->value]->getZ() == $values[$a->value]->getZ()) {
                return 0;
            }

            return $values[$b->value]->getZ() > $values[$a->value]->getZ() ? 1 : -1;
        });

        return $faces;
    }
}
