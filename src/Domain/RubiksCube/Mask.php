<?php

namespace App\Domain\RubiksCube;

enum Mask: string
{
    case FIRST_LAYER = 'firstLayer';
    case F2L = 'F2L';
    case LAST_LAYER = 'lastLayer';
    case LAST_LAYER_CORNERS = 'lastLayerCorners';
    case LAST_LAYER_EDGES = 'lastLayerEdges';
    case OLL = 'OLL';

    public function getStickersToMask(Face $face): array
    {
        $stickers = match ($this) {
            self::FIRST_LAYER => [
                Face::F->value => [0, 1, 2, 3, 4, 5],
                Face::B->value => [0, 1, 2, 3, 4, 5],
                Face::R->value => [0, 1, 2, 3, 4, 5],
                Face::L->value => [0, 1, 2, 3, 4, 5],
                Face::U->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
            ],
            self::F2L => [
                Face::F->value => [0, 1, 2],
                Face::B->value => [0, 1, 2],
                Face::R->value => [0, 1, 2],
                Face::L->value => [0, 1, 2],
                Face::U->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
            ],
            self::LAST_LAYER => [
                Face::F->value => [3, 4, 5, 6, 7, 8],
                Face::B->value => [3, 4, 5, 6, 7, 8],
                Face::R->value => [3, 4, 5, 6, 7, 8],
                Face::L->value => [3, 4, 5, 6, 7, 8],
                Face::U->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
            ],
            self::LAST_LAYER_CORNERS => [
                Face::U->value => [1, 3, 5, 7],
                Face::F->value => [1, 3, 4, 5, 6, 7, 8],
                Face::B->value => [1, 3, 4, 5, 6, 7, 8],
                Face::R->value => [1, 3, 4, 5, 6, 7, 8],
                Face::L->value => [1, 3, 4, 5, 6, 7, 8],
                Face::D->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
            ],
            self::LAST_LAYER_EDGES => [
                Face::U->value => [0, 2, 6, 8],
                Face::F->value => [0, 2, 3, 4, 5, 6, 7, 8],
                Face::B->value => [0, 2, 3, 4, 5, 6, 7, 8],
                Face::R->value => [0, 2, 3, 4, 5, 6, 7, 8],
                Face::L->value => [0, 2, 3, 4, 5, 6, 7, 8],
                Face::D->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
            ],
            self::OLL => [
                Face::R->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
                Face::F->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
                Face::D->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
                Face::L->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
                Face::B->value => [0, 1, 2, 3, 4, 5, 6, 7, 8],
            ],
        };

        return $stickers[$face->value] ?? [];
    }
}
