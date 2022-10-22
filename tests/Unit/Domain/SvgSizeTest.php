<?php

namespace App\Tests\Unit\Domain;

use App\Domain\SvgSize;
use PHPUnit\Framework\TestCase;

class SvgSizeTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $svg = SvgSize::fromInt(10);
        $this->assertEquals(10, json_encode($svg));
    }

    public function testFromOptionalInt(): void
    {
        $this->assertNull(SvgSize::fromOptionalInt(null));
    }

    public function testItShouldThrowWhenSizeSmallerThanOne(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid size provided');

        SvgSize::fromInt(0);
    }

    public function testItShouldThrowWhenSizeGreaterThan(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid size provided');

        SvgSize::fromInt(1025);
    }
}
