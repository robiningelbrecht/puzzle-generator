<?php

namespace App\Tests\Unit\Domain\RubiksCube\Axis;

use App\Domain\RubiksCube\Axis\Axis;
use PHPUnit\Framework\TestCase;

class AxisTest extends TestCase
{
    public function testCasesAsStrings(): void
    {
        $this->assertEquals(['x', 'y', 'z'], Axis::casesAsStrings());
    }
}
