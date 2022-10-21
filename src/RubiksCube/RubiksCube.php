<?php

namespace App\RubiksCube;

use App\Color;
use App\RubiksCube\Axis\Axis;
use App\RubiksCube\Axis\AxisOrientation;
use App\RubiksCube\ColorScheme\ColorScheme;
use App\RubiksCube\Rotation\Rotation;
use App\RubiksCube\Turn\Turn;
use App\RubiksCube\Turn\TurnType;

class RubiksCube implements \JsonSerializable
{
    private int $gridSize;
    private array $faces;
    private array $clockwiseStickerMapping;
    private array $counterClockwiseStickerMapping;
    private array $oppositeStickerMapping;
    private ?Algorithm $algorithm;

    private function __construct(
        private readonly CubeSize $size,
        private readonly Rotation $rotation,
        private readonly ColorScheme $colorScheme,
        private readonly Color $baseColor,
        private readonly ?Mask $mask = null,
    ) {
        $this->gridSize = pow($this->size->getValue(), 2);
        $this->clockwiseStickerMapping = [];
        $this->counterClockwiseStickerMapping = [];
        $this->oppositeStickerMapping = [];
        $this->algorithm = null;

        foreach (Face::cases() as $face) {
            $this->faces[$face->name] = [];
            for ($i = 0; $i < $this->gridSize; ++$i) {
                $this->faces[$face->name][] = $this->getColorScheme()->getColorForFace($face);
            }
        }

        for ($i = 1; $i <= $this->gridSize; ++$i) {
            $this->clockwiseStickerMapping[] = $this->getClockwiseSticker($i);
            $this->counterClockwiseStickerMapping[] = $this->getCounterClockwiseSticker($i);
            $this->oppositeStickerMapping[] = $this->getOppositeSticker($i);
        }
    }

    public static function fromValues(
        CubeSize $size,
        Rotation $rotation,
        ColorScheme $colorScheme,
        Color $baseColor,
        ?Mask $mask = null,
    ): self {
        return new self(
            $size,
            $rotation,
            $colorScheme,
            $baseColor,
            $mask,
        );
    }

    public function getSize(): CubeSize
    {
        return $this->size;
    }

    public function getRotation(): Rotation
    {
        return $this->rotation;
    }

    public function getColorScheme(): ColorScheme
    {
        return $this->colorScheme;
    }

    public function getBaseColor(): Color
    {
        return $this->baseColor;
    }

    public function getMask(): ?Mask
    {
        return $this->mask;
    }

    public function getFaces(): array
    {
        return $this->faces;
    }

    public function scramble(Algorithm $algorithm): self
    {
        $this->algorithm = $algorithm;
        if ($algorithm->isEmpty()) {
            return $this;
        }

        foreach ($algorithm->getTurns() as $turn) {
            $this->turn($turn);
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'size' => $this->getSize(),
            'rotation' => $this->getRotation(),
            'colorScheme' => $this->getColorScheme(),
            'baseColor' => $this->getBaseColor(),
            'mask' => $this->getMask(),
            'faces' => $this->getFaces(),
            'algorithm' => $this->algorithm,
            'stickerMapping' => [
                'clockWise' => $this->clockwiseStickerMapping,
                'counterClockWise' => $this->counterClockwiseStickerMapping,
                'opposite' => $this->oppositeStickerMapping,
            ],
        ];
    }

    public function rTurn(TurnType $turnType, int $slices = 1): self
    {
        $this->rotateFace(Face::R, $turnType);
        $offset = $this->getSize()->getValue() - $slices;
        $this->rotateXLayers($offset, TurnType::CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $slices);

        return $this;
    }

    public function lTurn(TurnType $turnType, int $slices = 1): self
    {
        $this->rotateFace(Face::L, $turnType);
        $this->rotateXLayers(0, TurnType::COUNTER_CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $slices);

        return $this;
    }

    public function uTurn(TurnType $turnType, int $slices = 1): self
    {
        $this->rotateFace(Face::U, $turnType);
        $this->rotateYLayers(0, TurnType::CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $slices);

        return $this;
    }

    public function dTurn(TurnType $turnType, int $slices = 1): self
    {
        $this->rotateFace(Face::D, $turnType);
        $offset = $this->getSize()->getValue() - $slices;
        $this->rotateYLayers($offset, TurnType::COUNTER_CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $slices);

        return $this;
    }

    public function fTurn(TurnType $turnType, int $slices = 1): self
    {
        $this->rotateFace(Face::F, $turnType);
        $offset = $this->getSize()->getValue() - $slices;
        $this->rotateZLayers($offset, TurnType::CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $slices);

        return $this;
    }

    public function bTurn(TurnType $turnType, int $slices = 1): self
    {
        $this->rotateFace(Face::B, $turnType);
        $this->rotateZLayers(0, TurnType::COUNTER_CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $slices);

        return $this;
    }

    public function mTurn(TurnType $turnType): self
    {
        if ($this->getSize()->getValue() < 2) {
            return $this;
        }

        $this->rotateXLayers(1, TurnType::COUNTER_CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $this->getSize()->getValue() - 2);

        return $this;
    }

    public function eTurn(TurnType $turnType): self
    {
        if ($this->getSize()->getValue() < 2) {
            return $this;
        }
        $this->rotateYLayers(1, TurnType::COUNTER_CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $this->getSize()->getValue() - 2);

        return $this;
    }

    public function sTurn(TurnType $turnType): self
    {
        if ($this->getSize()->getValue() < 2) {
            return $this;
        }
        $this->rotateZLayers(1, TurnType::CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $this->getSize()->getValue() - 2);

        return $this;
    }

    public function xTurn(TurnType $turnType): self
    {
        $this->rotateFace(Face::R, $turnType);
        $this->rotateFace(Face::L, $turnType->getOpposite());
        $this->rotateXLayers(0, TurnType::CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $this->getSize()->getValue());

        return $this;
    }

    public function yTurn(TurnType $turnType): self
    {
        $this->rotateFace(Face::U, $turnType);
        $this->rotateFace(Face::D, $turnType->getOpposite());
        $this->rotateYLayers(0, TurnType::CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $this->getSize()->getValue());

        return $this;
    }

    public function zTurn(TurnType $turnType): self
    {
        $this->rotateFace(Face::F, $turnType);
        $this->rotateFace(Face::B, $turnType->getOpposite());
        $this->rotateZLayers(0, TurnType::CLOCKWISE === $turnType, TurnType::DOUBLE === $turnType, $this->getSize()->getValue());

        return $this;
    }

    public function turn(Turn $turn): self
    {
        if (TurnType::NONE === $turn->getTurnType()) {
            return $this;
        }

        if ($turn->getSlices() >= $this->size->getValue()) {
            throw new \RuntimeException('The number of slices must be smaller than the cube size');
        }

        return match ($turn->getMove()) {
            Move::F => $this->fTurn($turn->getTurnType(), $turn->getSlices()),
            Move::B => $this->bTurn($turn->getTurnType(), $turn->getSlices()),
            Move::U => $this->uTurn($turn->getTurnType(), $turn->getSlices()),
            Move::D => $this->dTurn($turn->getTurnType(), $turn->getSlices()),
            Move::R => $this->rTurn($turn->getTurnType(), $turn->getSlices()),
            Move::L => $this->lTurn($turn->getTurnType(), $turn->getSlices()),
            Move::M => $this->mTurn($turn->getTurnType()),
            Move::E => $this->eTurn($turn->getTurnType()),
            Move::S => $this->sTurn($turn->getTurnType()),
            Move::x => $this->xTurn($turn->getTurnType()),
            Move::y => $this->yTurn($turn->getTurnType()),
            Move::z => $this->zTurn($turn->getTurnType()),
        };
    }

    private function getClockwiseSticker(int $stickerIndex): int
    {
        return $this->gridSize + 1 - $this->getCounterClockwiseSticker($stickerIndex);
    }

    private function getCounterClockwiseSticker(int $stickerIndex): int
    {
        return ($stickerIndex * $this->getSize()->getValue()) % ($this->gridSize + 1);
    }

    private function getOppositeSticker(int $stickerIndex): int
    {
        return $this->gridSize - $stickerIndex + 1;
    }

    private function rotateFace(Face $face, TurnType $turn): void
    {
        switch ($turn) {
            case TurnType::CLOCKWISE:
                $this->faces[$face->value] = array_map(fn (int $number) => $this->faces[$face->value][$number - 1], $this->clockwiseStickerMapping);
                break;

            case TurnType::COUNTER_CLOCKWISE:
                $this->faces[$face->value] = array_map(fn (int $number) => $this->faces[$face->value][$number - 1], $this->counterClockwiseStickerMapping);
                break;

            case TurnType::DOUBLE:
                $this->faces[$face->value] = array_reverse($this->faces[$face->value]);
                break;

            default:
                break;
        }
    }

    private function rotateAxis(
        int $offset,
        int $range,
        Axis $axis,
        array $faceOrder,
        bool $forward = true,
        bool $doubleTurn = false): void
    {
        if (!$forward) {
            $faceOrder = array_reverse($faceOrder);
        }
        $cubeSize = $this->getSize()->getValue();
        $originalValues = array_map(fn (Face $face) => $this->faces[$face->value], $faceOrder);

        for ($i = 0; $i < $cubeSize; ++$i) {
            for ($r = 0; $r < $range; ++$r) {
                $stickerIndex = $cubeSize * $i + ($offset + $r);
                for ($j = 0; $j < count($faceOrder); ++$j) {
                    /** @var \App\RubiksCube\Face $face */
                    $face = $faceOrder[$j];
                    $nextFace = $doubleTurn ? $faceOrder[($j + 2) % count($faceOrder)] : $faceOrder[($j + 1) % count($faceOrder)];
                    $valueIndex = $this->getAxisAlignedSticker($axis, $face, $stickerIndex + 1) - 1;
                    $nextFaceValueIndex = $this->getAxisAlignedSticker($axis, $nextFace, $stickerIndex + 1) - 1;
                    $this->faces[$face->value][$valueIndex] = $originalValues[($doubleTurn ? $j + 2 : $j + 1) % count($originalValues)][$nextFaceValueIndex];
                }
            }
        }
    }

    private function getAxisAlignedSticker(Axis $axis, Face $face, int $stickerIndex): int
    {
        if (!isset(AxisOrientation::get()[$axis->value][$face->value])) {
            throw new \RuntimeException(sprintf('Invalid axis face orientation value ${AXIS_ORIENTATION[%s][%s]}', $axis->name, $face->value));
        }

        return match (AxisOrientation::get()[$axis->value][$face->value]) {
            0 => $stickerIndex,
            1 => $this->getClockwiseSticker($stickerIndex),
            2 => $this->getOppositeSticker($stickerIndex),
            -1 => $this->getCounterClockwiseSticker($stickerIndex),
            default => throw new \RuntimeException(sprintf('Invalid axis face orientation value ${AXIS_ORIENTATION[%s][%s]}', $axis->name, $face->value))
        };
    }

    private function rotateXLayers(int $offset, bool $forward = true, bool $doubleTurn = false, int $range = 1): void
    {
        $this->rotateAxis(
            $offset,
            $range,
            Axis::X,
            [Face::U, Face::F, Face::D, Face::B],
            $forward,
            $doubleTurn
        );
    }

    private function rotateYLayers(int $offset, bool $forward = true, bool $doubleTurn = false, int $range = 1): void
    {
        $this->rotateAxis(
            $offset,
            $range,
            Axis::Y,
            [Face::L, Face::F, Face::R, Face::B],
            $forward,
            $doubleTurn
        );
    }

    private function rotateZLayers(int $offset, bool $forward = true, bool $doubleTurn = false, int $range = 1): void
    {
        $this->rotateAxis(
            $offset,
            $range,
            Axis::Z,
            [Face::U, Face::L, Face::D, Face::R],
            $forward,
            $doubleTurn
        );
    }
}
