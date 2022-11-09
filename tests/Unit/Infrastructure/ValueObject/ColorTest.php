<?php

namespace App\Tests\Unit\Infrastructure\ValueObject;

use App\Infrastructure\Exception\PuzzleException;
use App\Infrastructure\Json;
use App\Infrastructure\ValueObject\Color;
use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
    public function testFromString(): void
    {
        $this->assertEquals('"#FFFFFF"', Json::encode(Color::fromHexString('#ffffff')));
        $this->assertEquals('#FFFFFF', (string) Color::fromHexString('#ffffff'));
        $this->assertEquals('#FFFFFF', (string) Color::fromHexString('ffffff'));
    }

    public function testFromOptionalString(): void
    {
        $this->assertNull(Color::fromOptionalHexString(null));
        $this->assertEquals(Color::fromHexString('#ffffff'), Color::fromOptionalHexString('#ffffff'));
    }

    public function testIsTransparent(): void
    {
        $this->assertTrue(Color::transparent()->isTransparent());
        $this->assertFalse(Color::black()->isTransparent());
    }

    public function testItShouldThrowOnInvalidString(): void
    {
        $this->expectException(PuzzleException::class);
        $this->expectExceptionMessage('Invalid hex color <strong#invalid</strong>.');

        Color::fromHexString('invalid');
    }
}
