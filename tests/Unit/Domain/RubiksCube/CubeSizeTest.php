<?php

namespace App\Tests\Unit\Domain\RubiksCube;

use App\Domain\RubiksCube\CubeSize;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;

class CubeSizeTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $size = CubeSize::fromInt(3);
        $this->assertEquals(3, Json::encode($size));
    }

    public function testFromOptionalInt(): void
    {
        $this->assertNull(CubeSize::fromOptionalInt(null));
        $this->assertEquals(CubeSize::fromInt(3), CubeSize::fromOptionalInt(3));
    }

    public function testItShouldThrowWhenSizeSmallerThanOne(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid cube size "0" provided');

        CubeSize::fromInt(0);
    }

    public function testItShouldThrowWhenSizeGreaterThan(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid cube size "11" provided');

        CubeSize::fromInt(11);
    }
}
