<?php

namespace App\Tests\Unit\Domain\RubiksCube\Turn;

use App\Domain\RubiksCube\Turn\TurnType;
use PHPUnit\Framework\TestCase;

class TurnTypeTest extends TestCase
{
    public function testGetOpposite(): void
    {
        $this->assertEquals(TurnType::COUNTER_CLOCKWISE, TurnType::CLOCKWISE->getOpposite());
        $this->assertEquals(TurnType::CLOCKWISE, TurnType::COUNTER_CLOCKWISE->getOpposite());
        $this->assertEquals(TurnType::DOUBLE, TurnType::DOUBLE->getOpposite());
        $this->assertEquals(TurnType::NONE, TurnType::NONE->getOpposite());
    }
}
