<?php

namespace App\Domain\RubiksCube\Turn;

use App\Domain\RubiksCube\Move;

class Turn implements \JsonSerializable
{
    private function __construct(
        private readonly string $notation,
        private readonly Move $move,
        private readonly TurnType $turnType,
        private readonly int $slices,
    ) {
    }

    public static function fromMoveAndTurnTypeAndSlices(
        string $notation,
        Move $move,
        TurnType $turnType,
        int $slices,
    ): self {
        return new self($notation, $move, $turnType, $slices);
    }

    public function getOpposite(): self
    {
        $notation = $this->getNotation();
        if (in_array($this->getTurnType(), [TurnType::CLOCKWISE, TurnType::COUNTER_CLOCKWISE])) {
            $notation = str_contains($this->getNotation(), "'") ? str_replace("'", '', $this->getNotation()) : $this->getNotation()."'";
        }

        return self::fromMoveAndTurnTypeAndSlices(
            $notation,
            $this->getMove(),
            $this->getTurnType()->getOpposite(),
            $this->getSlices()
        );
    }

    public function getNotation(): string
    {
        return $this->notation;
    }

    public function getMove(): Move
    {
        return $this->move;
    }

    public function getTurnType(): TurnType
    {
        return $this->turnType;
    }

    public function getSlices(): int
    {
        return $this->slices;
    }

    public function jsonSerialize(): array
    {
        return [
            'notation' => $this->getNotation(),
            'move' => $this->getMove(),
            'turnType' => $this->getTurnType(),
            'slices' => $this->getSlices(),
        ];
    }
}
