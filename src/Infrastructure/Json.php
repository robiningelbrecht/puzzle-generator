<?php

namespace App\Infrastructure;

use Safe\Exceptions\JsonException;

class Json
{
    public static function encode(mixed $value, int $options = 0, int $depth = 512): string
    {
        try {
            return \Safe\json_encode($value, $options, $depth);
        } catch (JsonException $exception) {
            throw new JsonException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }

    public static function decode(string $json, bool $assoc = true, int $depth = 512, int $options = 0): mixed
    {
        if ($depth < 1) {
            throw new \RuntimeException('depth has to be a positive integer');
        }

        return \Safe\json_decode($json, $assoc, $depth, $options);
    }
}
