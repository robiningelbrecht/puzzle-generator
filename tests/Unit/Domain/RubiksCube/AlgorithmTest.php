<?php

namespace App\Tests\Unit\Domain\RubiksCube;

use App\Domain\RubiksCube\Algorithm;
use App\Infrastructure\Json;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class AlgorithmTest extends TestCase
{
    use MatchesSnapshots;

    private string $cubeSize;

    /**
     * @dataProvider provideStringAlgorithms
     */
    public function testFromString(string $algorithm, int $size): void
    {
        $this->cubeSize = $size.'x'.$size;
        $this->assertMatchesJsonSnapshot(Json::encode(Algorithm::fromString($algorithm)));
    }

    public function testFromOptionalString(): void
    {
        $this->assertEquals(Algorithm::fromOptionalString(), Algorithm::fromOptionalString(''));
        $this->assertEquals(Algorithm::fromOptionalString("F' U' R2 F U' F2 R' U R2"), Algorithm::fromString("F' U' R2 F U' F2 R' U R2"));
    }

    public function testItShouldThrowOnInvalidAlgorithm(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid move "P"');

        Algorithm::fromString('P');
    }

    public function testItShouldThrowOnInvalidAlgorithmCase2(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid move "DD"');

        Algorithm::fromString('DD');
    }

    public function testItShouldThrowOnInvalidAlgorithmCase3(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid move "DX"');

        Algorithm::fromString('DX');
    }

    public function testItShouldThrowOnInvalidAlgorithmCase4(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid move "X"');

        Algorithm::fromString('X');
    }

    public function provideStringAlgorithms(): array
    {
        return [
            // 2 x 2
            ["F' U' R2 F U' F2 R' U R2", 2],
            // 3 x 3
            ["R B2 R' U2 r' B2 U2 B2 D2 L' D' R' B' L B' D L' R2 U'", 3],
            // 4 x 4
            ["D2 F' D2 L2 F' L2 F' R2 F2 D2 R2 U F' R' B' L2 R' D F2 U2 Rw2 Uw2 U L Fw2 Rw2 F2 U' L Fw2 L U Fw' U F' L' Fw B2 D Uw Rw D2 Fw' F Uw", 4],
            // 5 x 5
            ["Uw' U' Lw D2 L' Dw' L2 R2 D Bw Lw' D B2 Fw2 L U' Fw2 F' Lw Fw' Uw' Rw U' Uw2 Rw2 D Rw2 L Uw Dw2 Lw Rw' U D' B2 Rw' U2 Dw L' F' R2 Rw Lw2 D R' Bw'", 5],
            // 6 x 6
            ["R' Bw 3Rw2 F Rw' Uw 3Fw Dw2 3Uw D2 Rw2 R' 3Uw2 R2 3Rw2 Uw2 F2 3Rw' Lw' F2 3Rw' Rw L' 3Uw U L' Uw' U2 D2 F", 6],
            // 7 x 7
            ["B 3Dw2 3Fw' Bw D R 3Uw' Fw U' Uw Bw B' L Lw' 3Rw' Uw' D 3Fw' B2 Rw' Uw2 3Lw2 3Dw R2 3Uw R' 3Uw2", 7],
        ];
    }

    protected function getSnapshotId(): string
    {
        return (new \ReflectionClass($this))->getShortName().'--'.
            $this->getName(false).'--'.
            $this->cubeSize;
    }
}
