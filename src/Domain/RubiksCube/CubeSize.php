<?php

namespace App\Domain\RubiksCube;

use App\Infrastructure\PuzzleException;

class CubeSize implements \JsonSerializable
{
    private function __construct(
        private readonly int $value
    ) {
        if ($this->value < 1 || $this->value > 10) {
            throw new PuzzleException(sprintf('Invalid cube size "%s" provided', $this->value));
        }
    }

    public static function fromInt(int $size): self
    {
        return new self($size);
    }

    public static function fromOptionalInt(int $value = null): ?self
    {
        if (is_null($value)) {
            return null;
        }

        return new self($value);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function jsonSerialize(): int
    {
        return $this->getValue();
    }
}
