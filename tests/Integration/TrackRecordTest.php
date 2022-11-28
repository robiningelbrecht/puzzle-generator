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

    public function testGetWithSortingOnTimeAsc(): void
    {
        $params = [
            'sort' => [
                'field' => 'time',
                'direction' => 'asc',
            ],
        ];

        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                sprintf('/track-record?%s', http_build_query($params))
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertMatchesHtmlSnapshot((string) $response->getBody());
    }

    public function testGetWithSortingOnTimeDesc(): void
    {
        $params = [
            'sort' => [
                'field' => 'time',
                'direction' => 'desc',
            ],
        ];

        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                sprintf('/track-record?%s', http_build_query($params))
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertMatchesHtmlSnapshot((string) $response->getBody());
    }

    public function testGetWithSortingOnDateAsc(): void
    {
        $params = [
            'sort' => [
                'field' => 'date',
                'direction' => 'asc',
            ],
        ];

        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                sprintf('/track-record?%s', http_build_query($params))
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertMatchesHtmlSnapshot((string) $response->getBody());
    }
}
