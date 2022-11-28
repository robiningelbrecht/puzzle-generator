<?php

namespace App\Domain\TrackRecord;

class TrackRecordFilePath
{
    private function __construct(
        private readonly string $path
    ) {
    }

    public static function fromString(string $path): self
    {
        return new self($path);
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
