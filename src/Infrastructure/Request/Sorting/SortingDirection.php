<?php

namespace App\Infrastructure\Request\Sorting;

enum SortingDirection: string
{
    case ASCENDING = 'asc';
    case DESCENDING = 'desc';

    public function getOpposite(): self
    {
        return match ($this) {
            self::ASCENDING => self::DESCENDING,
            self::DESCENDING => self::ASCENDING,
        };
    }
}
