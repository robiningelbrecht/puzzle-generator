<?php

namespace App\Infrastructure\Request\Sorting;

class Sorting
{
    private function __construct(
        private readonly SortableField $sortableFieldName,
        private readonly SortingDirection $sortingDirection)
    {
    }

    public static function with(SortableField $fieldName, SortingDirection $sortingDirection): self
    {
        return new self($fieldName, $sortingDirection);
    }

    public function getSortableFieldName(): SortableField
    {
        return $this->sortableFieldName;
    }

    public function getSortingDirection(): SortingDirection
    {
        return $this->sortingDirection;
    }
}
