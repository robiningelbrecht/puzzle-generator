<?php

namespace App\RubiksCube;

enum Move: string
{
    case F = 'F';
    case U = 'U';
    case R = 'R';
    case L = 'L';
    case D = 'D';
    case B = 'B';
    case M = 'M';
    case E = 'E';
    case S = 'S';
    case x = 'x';
    case y = 'y';
    case z = 'z';
}
