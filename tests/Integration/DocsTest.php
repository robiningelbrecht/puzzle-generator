<?php

namespace App\Tests\Integration;

use App\Tests\WebTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class DocsTest extends WebTestCase
{
    use MatchesSnapshots;

    public function testGet(): void
    {
        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                '/'
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
    }
}
