<?php

namespace App\Domain\Svg;

use App\Domain\PuzzleException;

class Size implements \JsonSerializable
{
    private function __construct(
        private readonly int $size
    ) {
        if ($this->size < 1 || $this->size > 1024) {
            throw new PuzzleException(sprintf('Invalid svg size "%s" provided', $this->size));
        }
    }

    public static function fromInt(int $size): self
    {
        return new self($size);
    }

    public static function fromOptionalInt(int $size = null): ?self
    {
        if (is_null($size)) {
            return null;
        }

        return new self($size);
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function jsonSerialize(): int
    {
        return $this->getSize();
    }
}
