<?php

namespace App\RubiksCube\Axis;

// Face's orientation related to other faces on a given axis
// the number represents the number of turns necessary
// to orient the face in the same direction
use App\RubiksCube\Face;

final class AxisOrientation
{

    public static function get(): array
    {
        return [
            Axis::X->value => [
                Face::U->value => 0,
                Face::B->value => 2,
                Face::F->value => 0,
                Face::D->value => 0,
            ],
            Axis::Y->value => [
                Face::B->value => -1,
                Face::F->value => -1,
                Face::L->value => -1,
                Face::R->value => -1,
            ],
            Axis::Z->value => [
                Face::U->value => -1,
                Face::D->value => 1,
                Face::L->value => 0,
                Face::R->value => 2,
            ],
        ];
    }
}
