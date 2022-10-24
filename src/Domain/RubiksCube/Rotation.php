<?php

namespace App\Domain\RubiksCube;

use App\Domain\RubiksCube\Axis\Axis;

class Rotation implements \JsonSerializable
{
    public const DEFAULT_X = -34;
    public const DEFAULT_Y = 45;

    private function __construct(
        private readonly Axis $axis,
        private readonly int $value,
    ) {
        if ($value < -360 || $value > 360) {
            throw new \RuntimeException(sprintf('Invalid number (%s) of rotation degrees provided', $this->value));
        }
    }

    public static function fromAxisAndValue(
        Axis $axis,
        int $value,
    ): self {
        return new self($axis, $value);
    }

    public static function fromMap(array $map): array
    {
        return array_map(fn (array $item) => self::fromAxisAndValue(
            Axis::from($item['axis']),
            $map['value'],
        ), $map);
    }

    public function getAxis(): Axis
    {
        return $this->axis;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function jsonSerialize(): array
    {
        return [
            'axis' => $this->getAxis(),
            'value' => $this->getValue(),
        ];
    }
}
