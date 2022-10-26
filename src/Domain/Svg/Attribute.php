<?php

namespace App\Domain\Svg;

class Attribute implements \Stringable, \JsonSerializable
{
    private function __construct(
        private readonly string $name,
        private readonly string $value,
    ) {
    }

    public static function fromNameAndValue(
        string $name,
        string $value
    ): self {
        return new self($name, $value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getName().'="'.$this->getValue().'"';
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue(),
        ];
    }
}
