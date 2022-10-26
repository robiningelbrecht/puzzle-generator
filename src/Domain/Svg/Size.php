<?php

namespace App\Domain\Svg;

use App\Domain\PuzzleException;

class Size implements \JsonSerializable, \Stringable
{
    private function __construct(
        private readonly int $value
    ) {
        if ($this->value < 1 || $this->value > 1024) {
            throw new PuzzleException(sprintf('Invalid svg size "%s" provided', $this->value));
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

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->getValue();
    }

    public function jsonSerialize(): int
    {
        return $this->getValue();
    }
}
