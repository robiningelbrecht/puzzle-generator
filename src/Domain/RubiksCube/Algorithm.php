<?php

namespace App\Domain\RubiksCube;

use App\Domain\RubiksCube\Turn\Turn;
use App\Domain\RubiksCube\Turn\TurnType;
use App\Infrastructure\Exception\PuzzleException;
use App\Infrastructure\Implode;

use function Safe\preg_match;

class Algorithm implements \JsonSerializable, \Stringable
{
    private const REGEX = "/^(?<slices>[2-9]+)?(?<move>[UuFfRrDdLlBbMESxyz])(?<outerBlockIndicator>w)?(?<turnType>\d+\\'|\\'\d+|\d+|\\')?$/";

    private array $turns;

    private function __construct(
        Turn ...$turns
    ) {
        $this->turns = $turns;
    }

    public function isEmpty(): bool
    {
        return empty($this->turns);
    }

    public static function fromString(string $string): self
    {
        $turns = [];
        foreach (explode(' ', $string) as $notation) {
            if (!preg_match(self::REGEX, $notation, $matches)) {
                throw new PuzzleException(sprintf('Invalid move <strong>%s</strong>, valid moves are %s', $notation, Implode::wrapElementsWithTag(', ', 'code', Move::casesAsStrings())));
            }

            $move = $matches['move'];
            $outerBlockIndicator = $matches['outerBlockIndicator'] ?? '';
            $turnType = $matches['turnType'] ?? '';
            $isLowerCaseMove = $move === strtolower($move) && !in_array($move, ['x', 'y', 'z']);
            $slices = self::getSlices($matches['slices'] ?: null, $outerBlockIndicator);

            if ($isLowerCaseMove) {
                $move = strtoupper($move);
            }

            $turns[] = Turn::fromMoveAndTurnTypeAndSlices(
                $notation,
                Move::from($move),
                self::getTurnTypeByTurnAbbreviation($turnType),
                $isLowerCaseMove ? 2 : $slices,
            );
        }

        return new self(...$turns);
    }

    public static function fromOptionalString(string $string = null): self
    {
        if (empty($string)) {
            return new self();
        }

        return self::fromString($string);
    }

    public function getTurns(): array
    {
        return $this->turns;
    }

    public function reverse(): self
    {
        $this->turns = array_map(
            fn (Turn $turn) => $turn->getOpposite(),
            array_reverse($this->turns)
        );

        return $this;
    }

    public function jsonSerialize(): array
    {
        return $this->getTurns();
    }

    public function __toString(): string
    {
        return implode(' ', array_map(fn (Turn $turn) => $turn->getNotation(), $this->getTurns()));
    }

    private static function getTurnTypeByTurnAbbreviation(string $turnAbbreviation): TurnType
    {
        switch ($turnAbbreviation) {
            case '':
                return TurnType::CLOCKWISE;
            case "'":
                return TurnType::COUNTER_CLOCKWISE;
            case '2':
            case "2'":
            case "'2":
                return TurnType::DOUBLE;
            default:
                // Attempt to parse non-standard turn type
                // (for invalid but reasonable moves like "y3")
                $turns = $turnAbbreviation % 4;

                return match ($turns) {
                    0 => TurnType::NONE,
                    1 => TurnType::CLOCKWISE,
                    3 => TurnType::COUNTER_CLOCKWISE,
                    default => throw new PuzzleException('Invalid turnAbbreviation')
                };
        }
    }

    private static function getSlices(int $slices = null, string $outerBlockIndicator = null): int
    {
        if (!$outerBlockIndicator && $slices) {
            throw new PuzzleException("Invalid move: Cannot specify num slices if outer block move indicator 'w' is not present");
        }

        if ($outerBlockIndicator && !$slices) {
            return 2;
        }

        return $slices ?? 1;
    }
}
