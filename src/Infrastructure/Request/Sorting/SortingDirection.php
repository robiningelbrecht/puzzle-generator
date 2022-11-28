<?php

namespace App\Infrastructure\Request\Sorting;

enum SortingDirection: string
{
    case ASCENDING = 'asc';
    case DESCENDING = 'desc';
}
