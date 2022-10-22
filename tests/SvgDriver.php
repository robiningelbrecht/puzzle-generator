<?php

namespace App\Tests;

use Spatie\Snapshots\Drivers\XmlDriver;

class SvgDriver extends XmlDriver
{
    public function extension(): string
    {
        return 'svg';
    }
}
