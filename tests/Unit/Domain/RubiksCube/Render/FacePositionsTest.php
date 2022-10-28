<?php

namespace App\Tests\Unit\Domain\RubiksCube\Render;

use App\Domain\RubiksCube\Axis\Axis;
use App\Domain\RubiksCube\Face;
use App\Domain\RubiksCube\Render\FacePositions;
use App\Domain\RubiksCube\Rotation;
use PHPUnit\Framework\TestCase;

class FacePositionsTest extends TestCase
{
    public function testGetFaces(): void
    {
        $facePositions = FacePositions::fromRotations(
            Rotation::fromAxisAndValue(Axis::Y, Rotation::DEFAULT_Y),
            Rotation::fromAxisAndValue(Axis::X, Rotation::DEFAULT_X)
        );

        $this->assertEquals([Face::U, Face::R, Face::F], $facePositions->getVisibleFaces());
        $this->assertEquals([Face::B, Face::L, Face::D], $facePositions->getHiddenFaces());
    }
}
