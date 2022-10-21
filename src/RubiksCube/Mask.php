<?php

namespace App\RubiksCube;

enum Mask: string
{
    case LINE  ='line';
    case FL = 'fl';
    case F2L = 'f2l';
    case F2L_1 = 'f2l_1';
    case F2L_2 = 'f2l_2';
    case F2L_3 = 'f2l_3';
    case F2B = 'f2b';
    case F2L_SM = 'f2l_sm';
    case LL = 'll';
    case CLL = 'cll';
    case ELL = 'ell';
    case OLL = 'oll';
    case OCLL = 'ocll';
    case OELL = 'oell';
    case COLL = 'coll';
    case OCELL = 'ocell';
    case WV = 'wv';
    case VH = 'vh';
    case ELS = 'els';
    case CLS = 'cls';
    case CMLL = 'cmll';
    case CROSS = 'cross';

}
