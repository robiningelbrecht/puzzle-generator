<?php

namespace App\Infrastructure;

use App\Domain\RubiksCube\Axis\Axis;
use App\Infrastructure\ValueObject\Position;

class Math
{
    public const PI = M_PI;

    public static function rotate(Position $position, Axis $axis, float $radians): Position
    {
        return match ($axis) {
            Axis::X => Position::fromXYZ(
                $position->getX(),
                $position->getZ() * sin($radians) + $position->getY() * cos($radians),
                $position->getZ() * cos($radians) - $position->getY() * sin($radians)
            ),
            Axis::Y => Position::fromXYZ(
                $position->getX() * cos($radians) + $position->getZ() * sin($radians),
                $position->getY(),
                -$position->getX() * sin($radians) + $position->getZ() * cos($radians)
            ),
            Axis::Z => Position::fromXYZ(
                $position->getX() * cos($radians) - $position->getY() * sin($radians),
                $position->getX() * sin($radians) + $position->getY() * cos($radians),
                $position->getZ()
            ),
        };
    }

    public static function translate(Position $position, Position $translation): Position
    {
        return Position::fromXYZ(
            $position->getX() + $translation->getX(),
            $position->getY() + $translation->getY(),
            $position->getZ() + $translation->getZ()
        );
    }

    public static function scale(Position $position, float $factor): Position
    {
        return Position::fromXYZ(
            $position->getX() * $factor,
            $position->getY() * $factor,
            $position->getZ() * $factor,
        );
    }

    public static function transScale(Position $position, Position $translation, float $factor): Position
    {
        return Math::translate(Math::scale(Math::translate(
            $position,
            Position::fromXYZ(
                -$translation->getX(),
                -$translation->getY(),
                -$translation->getZ()
            )
        ), $factor), $translation);
    }

    public static function project(Position $position, int $distance): Position
    {
        return Position::fromXYZ(
            $position->getX() * $distance / $position->getZ(),
            $position->getY() * $distance / $position->getZ(),
            $position->getZ()
        );
    }
}
