<?php

namespace App\Domain\RubiksCube;

enum View: string
{
    case THREE_D = '3D';
    case TOP = 'top';
    case NET = 'net';
}
