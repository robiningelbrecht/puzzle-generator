<?php

namespace App\Infrastructure\ValueObject;

class Time implements \Stringable
{
    private function __construct(
        private readonly int $milliSeconds
    ) {
    }

    public static function fromMilliSeconds(int $milliSeconds): self
    {
        return new self($milliSeconds);
    }

    public function __toString(): string
    {
        $milliSeconds = $this->milliSeconds % 1000;
        $input = floor($this->milliSeconds / 1000);

        $seconds = $input % 60;
        $input = floor($input / 60);

        $minutes = $input % 60;

        return str_pad((string) $minutes, 2, '0', STR_PAD_LEFT).':'.
            str_pad((string) $seconds, 2, '0', STR_PAD_LEFT).'.<small>'.str_pad((string) $milliSeconds, 2, '0', STR_PAD_LEFT).'</small>';
    }
}
