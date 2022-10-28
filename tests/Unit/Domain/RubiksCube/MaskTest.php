<?php

namespace App\Tests\Unit\Domain\RubiksCube;

use App\Domain\RubiksCube\Face;
use App\Domain\RubiksCube\Mask;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class MaskTest extends TestCase
{
    use MatchesSnapshots;

    public function testGetStickersToMask(): void
    {
        $json = [];
        foreach (Mask::cases() as $mask) {
            foreach (Face::cases() as $face) {
                $json[$mask->value][$face->value] = $mask->getStickersToMask($face);
            }
        }

        $this->assertMatchesJsonSnapshot($json);
    }
}
