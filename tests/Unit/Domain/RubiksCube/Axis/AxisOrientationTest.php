<?php

namespace App\Tests\Unit\Domain\RubiksCube\Axis;

use App\Domain\RubiksCube\Axis\AxisOrientation;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class AxisOrientationTest extends TestCase
{
    use MatchesSnapshots;

    public function testGet(): void
    {
        $this->assertMatchesJsonSnapshot(AxisOrientation::get());
    }
}
