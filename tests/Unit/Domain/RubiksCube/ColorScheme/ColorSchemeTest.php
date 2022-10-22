<?php

namespace App\Tests\Unit\Domain\RubiksCube\ColorScheme;

use App\Domain\Color;
use App\Domain\RubiksCube\ColorScheme\ColorSchemeBuilder;
use App\Domain\RubiksCube\Face;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class ColorSchemeTest extends TestCase
{
    use MatchesSnapshots;

    public function testWithNullValues(): void
    {
        $colorScheme = ColorSchemeBuilder::fromDefaults()
            ->withColorForU(null)
            ->withColorForB(null)
            ->withColorForD(null)
            ->withColorForF(null)
            ->withColorForL(null)
            ->withColorForR(null)
            ->build();

        $this->assertEquals($colorScheme, ColorSchemeBuilder::fromDefaults()->build());
        $this->assertMatchesJsonSnapshot(Json::encode($colorScheme));
    }

    public function testWithAllValues(): void
    {
        $colorScheme = ColorSchemeBuilder::fromDefaults()
            ->withColorForU(Color::yellow())
            ->withColorForB(Color::blue())
            ->withColorForD(Color::orange())
            ->withColorForF(Color::green())
            ->withColorForL(Color::white())
            ->withColorForR(Color::black())
            ->build();

        $this->assertMatchesJsonSnapshot(Json::encode($colorScheme));
    }

    public function testGetColorForFace(): void
    {
        $colorScheme = ColorSchemeBuilder::fromDefaults()
            ->withColorForU(Color::yellow())
            ->withColorForB(Color::blue())
            ->withColorForD(Color::orange())
            ->withColorForF(Color::green())
            ->withColorForL(Color::white())
            ->withColorForR(Color::black())
            ->build();

        $this->assertEquals(Color::yellow(), $colorScheme->getColorForFace(Face::U));
        $this->assertEquals(Color::black(), $colorScheme->getColorForFace(Face::R));
        $this->assertEquals(Color::white(), $colorScheme->getColorForFace(Face::L));
        $this->assertEquals(Color::orange(), $colorScheme->getColorForFace(Face::D));
        $this->assertEquals(Color::blue(), $colorScheme->getColorForFace(Face::B));
        $this->assertEquals(Color::green(), $colorScheme->getColorForFace(Face::F));
    }

    public function testItShouldThrowWhenNonUniqueColors(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid ColorScheme provided, all colors have to be unique.');

        ColorSchemeBuilder::fromDefaults()
            ->withColorForU(Color::yellow())
            ->withColorForB(Color::yellow())
            ->withColorForD(Color::orange())
            ->withColorForF(Color::green())
            ->withColorForL(Color::white())
            ->withColorForR(Color::black())
            ->build();
    }
}
