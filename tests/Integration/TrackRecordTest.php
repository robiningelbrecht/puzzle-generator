<?php

namespace App\Tests\Integration;

use App\Tests\WebTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class TrackRecordTest extends WebTestCase
{
    use MatchesSnapshots;

    public function testGet(): void
    {
        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                '/track-record'
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertMatchesHtmlSnapshot((string) $response->getBody());
    }
}
