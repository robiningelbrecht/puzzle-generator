<?php

namespace App\Tests\Integration;

use App\Tests\SvgDriver;
use App\Tests\WebTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class RubiksCubeTest extends WebTestCase
{
    use MatchesSnapshots;

    public function testGetRubiksCubeAsSvg(): void
    {
        $params = [
            'cube' => [
                'size' => 3,
            ],
        ];

        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                sprintf('/cube?%s', http_build_query($params))
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertMatchesSnapshot((string) $response->getBody(), new SvgDriver());
    }

    public function testGetRubiksCubeAsJson(): void
    {
        $params = [
            'json' => 1,
            'cube' => [
                'size' => 5,
            ],
        ];

        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                sprintf('/cube?%s', http_build_query($params))
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertMatchesJsonSnapshot((string) $response->getBody());
    }

    public function testWhenRouteNotFound(): void
    {
        $response = $this->getApp()->handle(
            $this->createRequest('GET', '/not-found'),
        );

        $this->assertEquals(404, $response->getStatusCode());
    }
}
