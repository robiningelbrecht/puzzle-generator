<?php

namespace App\Domain\RubiksCube;

use App\Domain\RubiksCube\Axis\Axis;
use App\Infrastructure\Exception\PuzzleException;

class Rotation implements \JsonSerializable
{
    public const DEFAULT_X = -34;
    public const DEFAULT_Y = 45;

    private function __construct(
        private readonly Axis $axis,
        private readonly int $value,
    ) {
        if ($value < -360 || $value > 360) {
            throw new PuzzleException(sprintf('Invalid number (%s) of rotation degrees provided', $this->value));
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
        return array_map(function (array $item) {
            if (empty($item['axis']) || empty($item['value'])) {
                throw new PuzzleException('Invalid rotation provided. "axis" and "value" are required');
            }

            try {
                return self::fromAxisAndValue(
                    Axis::from($item['axis']),
                    $item['value'],
                );
            } catch (\Throwable) {
                throw new PuzzleException(sprintf('Invalid axis "%s" provided', $item['axis']));
            }
        }, $map);
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
