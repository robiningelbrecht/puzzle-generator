<?php

namespace App\Infrastructure\ValueObject;

class Time
{
    private function __construct(
        private readonly int $milliSeconds
    ) {
    }

    public static function fromMilliSeconds(int $milliSeconds): self
    {
        return new self($milliSeconds);
    }

    public function getMilliSeconds(): int
    {
        return $this->milliSeconds % 1000;
    }

    public function getSeconds(): int
    {
        return floor($this->milliSeconds / 1000) % 60;
    }

    public function getMinutes(): int
    {
        return floor($this->milliSeconds / 1000 / 60) % 60;
    }
}
