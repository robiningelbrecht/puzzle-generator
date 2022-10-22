<?php

namespace App\Tests\Unit\Infrastructure;

use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;
use Safe\Exceptions\JsonException;
use Spatie\Snapshots\MatchesSnapshots;

class JsonTest extends TestCase
{
    use MatchesSnapshots;

    public function testEncodeAndDecode(): void
    {
        $array = ['random' => ['array' => ['with', 'children']]];

        $encoded = Json::encode($array);
        $this->assertMatchesJsonSnapshot($encoded);

        $this->assertEquals($array, Json::decode($encoded));
    }

    public function testItShouldThrowOnInvalidJson(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Type is not supported');

        $fp = fopen(__DIR__.'/test.txt', 'w');
        Json::encode($fp);
    }

    public function testItShouldThrowOnInvalidDepth(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('depth has to be a positive integer');

        Json::decode('', true, 0);
    }
}
