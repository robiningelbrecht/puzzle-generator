<?php

namespace App\Tests\Integration;

use App\Tests\SvgDriver;
use App\Tests\WebTestCase;
use Slim\Exception\HttpNotFoundException;
use Spatie\Snapshots\MatchesSnapshots;

class RubiksCubeTest extends WebTestCase
{
    use MatchesSnapshots;

    public function testGetRubiksCube(): void
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

    public function testItShouldThrowWhenRouteNotFound(): void
    {
        $this->expectException(HttpNotFoundException::class);

        $this->getApp()->handle(
            $this->createRequest('GET', '/not-found'),
        );
    }
}
