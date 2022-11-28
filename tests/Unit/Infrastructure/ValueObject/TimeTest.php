<?php

namespace App\Tests\Unit\Infrastructure\ValueObject;

use App\Infrastructure\ValueObject\Time;
use PHPUnit\Framework\TestCase;

class TimeTest extends TestCase
{
    public function testGetters(): void
    {
        $time = Time::fromMilliSeconds(235678);
        $this->assertEquals(3, $time->getMinutes());
        $this->assertEquals(55, $time->getSeconds());
        $this->assertEquals(678, $time->getMilliSeconds());
    }
}
