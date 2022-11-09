<?php

namespace App\Infrastructure;

class Implode
{
    public static function wrapElementsWithTag(string $separator, string $tag, array $array): string
    {
        return '<'.$tag.'>'.implode('</'.$tag.'>'.$separator.'<'.$tag.'>', $array).'</'.$tag.'>';
    }
}
