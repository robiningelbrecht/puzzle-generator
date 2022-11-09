<?php

namespace App\Domain\RubiksCube\Axis;

enum Axis: string
{
    case X = 'x';
    case Y = 'y';
    case Z = 'z';

    public static function casesAsStrings(): array
    {
        return array_map(fn (Axis $axis) => $axis->value, self::cases());
    }
}
