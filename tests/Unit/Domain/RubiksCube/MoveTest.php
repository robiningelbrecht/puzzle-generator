<?php

namespace App\Tests\Unit\Domain\RubiksCube;

use App\Domain\RubiksCube\Move;
use PHPUnit\Framework\TestCase;

class MoveTest extends TestCase
{
    public function testCasesAsStrings(): void
    {
        $this->assertEquals(['F', 'U', 'R', 'L', 'D', 'B', 'M', 'E', 'S', 'x', 'y', 'z'], Move::casesAsStrings());
    }
}
