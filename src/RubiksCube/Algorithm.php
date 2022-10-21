<?php

namespace App\RubiksCube;

class Algorithm implements \JsonSerializable
{
    private const REGEX = "/(?<slices>[2-9]+)?(?<move>[UuFfRrDdLlBbMESxyz])(?<outerBlockIndicator>w)?(?<turnType>\d+\\'|\\'\d+|\d+|\\')?/";

    private readonly array $turns;

    private function __construct(
        Turn ...$turns
    )
    {
        $this->turns = $turns;
    }

    public function isEmpty(): bool
    {
        return empty($this->turns);
    }

    public static function fromString(string $string): self
    {
        $turns = [];
        foreach (explode(' ', $string) as $move) {
            if (!preg_match(self::REGEX, $move, $matches)) {
                throw new \RuntimeException(sprintf('Invalid move "%s"', $move));
            }

            $slices = (int)$matches['slices'] ?? 1;
            $move = $matches['move'];
            $outerBlockIndicator = $matches['outerBlockIndicator'] ?? '';
            $turnType = $matches['turnType'] ?? '';
            $isLowerCaseMove = $move === strtolower($move) && !in_array($move, ['x', 'y', 'z']);

            if ($isLowerCaseMove) {
                $move = strtoupper($move);
            }

            $turns[] = Turn::fromMoveAndTurnTypeAndSlices(
                Move::from($move),
                self::getTurnTypeByTurnAbbreviation($turnType),
                $isLowerCaseMove ? 2 : self::getSlices($slices, $outerBlockIndicator),
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

    public function jsonSerialize(): array
    {
        return $this->getTurns();
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
                // Attempt to parse non standard turn type
                // (for invalid but reasonable moves like "y3")
                $reversed = false;
                if (str_starts_with($turnAbbreviation, "'") || str_ends_with($turnAbbreviation, "'")) {
                    $reversed = true;
                    $turnAbbreviation = (int)filter_var($turnAbbreviation, FILTER_SANITIZE_NUMBER_INT);
                }

                $turns = $turnAbbreviation % 4;

                return match ($turns) {
                    0 => TurnType::NONE,
                    1 => $reversed ? TurnType::COUNTER_CLOCKWISE : TurnType::CLOCKWISE,
                    2 => TurnType::DOUBLE,
                    3 => $reversed ? TurnType::CLOCKWISE : TurnType::COUNTER_CLOCKWISE,
                    default => throw new \RuntimeException('Invalid turnAbbreviation')
                };
        }
    }

    private static function getSlices(int $slices = null, string $outerBlockIndicator = null): int
    {
        if (!$outerBlockIndicator && $slices) {
            throw new \RuntimeException("Invalid move: Cannot specify num slices if outer block move indicator 'w' is not present");
        }

        if ($outerBlockIndicator && !$slices) {
            return 2;
        }

        if (!$outerBlockIndicator && !$slices) {
            return 1;
        }

        return $slices;
    }
}
