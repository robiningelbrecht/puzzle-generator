<?php

namespace App;

class Color implements \Stringable, \JsonSerializable
{
    private function __construct(
        private readonly string $value
    ) {
    }

    public static function fromHexString(string $hex): self
    {
        if (!str_starts_with($hex, '#')) {
            $hex = '#'.$hex;
        }
        if (!preg_match('/^#[a-f0-9]{6}$/i', $hex)) {
            throw new \RuntimeException('Invalid hex color');
        }

        return new self($hex);
    }

    public static function fromOptionalHexString(string $hex = null): ?self
    {
        if (is_null($hex)) {
            return null;
        }

        return self::fromHexString($hex);
    }

    public static function yellow(): self
    {
        return self::fromHexString('#FEFE00');
    }

    public static function white(): self
    {
        return self::fromHexString('#FFFFFF');
    }

    public static function black(): self
    {
        return self::fromHexString('#000000');
    }

    public static function red(): self
    {
        return self::fromHexString('#EE0000');
    }

    public static function blue(): self
    {
        return self::fromHexString('#0000F2');
    }

    public static function orange(): self
    {
        return self::fromHexString('#FFA100');
    }

    public static function green(): self
    {
        return self::fromHexString('#00D800');
    }

    public function getValue(): string
    {
        return strtoupper($this->value);
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public function jsonSerialize(): string
    {
        return $this->getValue();
    }
}
