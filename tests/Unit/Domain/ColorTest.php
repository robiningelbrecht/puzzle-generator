<?php

namespace App\Tests\Unit\Domain;

use App\Domain\Color;
use App\Infrastructure\Json;
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

    public function testItShouldThrowOnInvalidString(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid hex color "#invalid"');

        Color::fromHexString('invalid');
    }
}
