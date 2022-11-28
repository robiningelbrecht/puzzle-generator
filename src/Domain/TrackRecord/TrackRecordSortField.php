<?php

namespace App\Domain\TrackRecord;

use App\Infrastructure\Request\Sorting\SortableField;

enum TrackRecordSortField: string implements SortableField
{
    case DATE = 'date';
    case TIME = 'time';
}
