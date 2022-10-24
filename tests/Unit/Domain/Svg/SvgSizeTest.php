<?php

namespace App\Tests\Unit\Domain\Svg;

use App\Domain\Svg\SvgSize;
use App\Infrastructure\Json;
use App\Infrastructure\PuzzleException;
use PHPUnit\Framework\TestCase;

class SvgSizeTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $svg = SvgSize::fromInt(10);
        $this->assertEquals(10, Json::encode($svg));
    }

    public function testFromOptionalInt(): void
    {
        $this->assertNull(SvgSize::fromOptionalInt(null));
        $this->assertEquals(SvgSize::fromInt(10), SvgSize::fromOptionalInt(10));
    }

    public function testItShouldThrowWhenSizeSmallerThanOne(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid svg size "0" provided');

        SvgSize::fromInt(0);
    }

    public function testItShouldThrowWhenSizeGreaterThan(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid svg size "1025" provided');

        SvgSize::fromInt(1025);
    }
}
