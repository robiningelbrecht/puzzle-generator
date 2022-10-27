<?php

namespace App\Tests\Unit\Domain\RubiksCube;

use App\Domain\RubiksCube\Size;
use App\Infrastructure\Exception\PuzzleException;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;

class SizeTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $size = Size::fromInt(3);
        $this->assertEquals(3, Json::encode($size));
    }

    public function testFromOptionalInt(): void
    {
        $this->assertNull(Size::fromOptionalInt(null));
        $this->assertEquals(Size::fromInt(3), Size::fromOptionalInt(3));
    }

    public function testItShouldThrowWhenSizeSmallerThanOne(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid cube size "0" provided');

        Size::fromInt(0);
    }

    public function testItShouldThrowWhenSizeGreaterThan(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid cube size "11" provided');

        Size::fromInt(11);
    }
}
