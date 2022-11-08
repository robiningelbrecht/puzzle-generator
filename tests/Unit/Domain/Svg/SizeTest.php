<?php

namespace App\Tests\Unit\Domain\Svg;

use App\Domain\Svg\Size;
use App\Infrastructure\Exception\PuzzleException;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;

class SizeTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $svg = Size::fromInt(10);
        $this->assertEquals(10, Json::encode($svg));
    }

    public function testFromOptionalInt(): void
    {
        $this->assertNull(Size::fromOptionalInt(null));
        $this->assertEquals(Size::fromInt(10), Size::fromOptionalInt(10));
    }

    public function testItShouldThrowWhenSizeSmallerThanOne(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid svg size <strong>0</strong> provided, valid range is 1 - 1024.');

        Size::fromInt(0);
    }

    public function testItShouldThrowWhenSizeGreaterThan(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid svg size <strong>1025</strong> provided, valid range is 1 - 1024.');

        Size::fromInt(1025);
    }
}
