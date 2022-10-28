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

    public function testGetRubiksCubeWithAlgorithmAsSvg(): void
    {
        $params = [
            'cube' => [
                'size' => 3,
                'algorithm' => 'R U',
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

    public function testGetRubiksCubeWithCaseAsSvg(): void
    {
        $params = [
            'cube' => [
                'size' => 3,
                'case' => 'R U',
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

    public function testGetRubiksCubeWithMaskAsSvg(): void
    {
        $params = [
            'cube' => [
                'size' => 3,
                'algorithm' => 'R U',
                'mask' => 'OLL',
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

    public function testGetRubiksCubeBigSizeAsSvg(): void
    {
        $params = [
            'cube' => [
                'size' => 6,
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

    public function testGetRubiksCubeInTopViewAsSvg(): void
    {
        $params = [
            'view' => 'top',
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

    public function testGetRubiksCubeInNetViewAsSvg(): void
    {
        $params = [
            'view' => 'net',
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

    public function testWhenBothAlgorithmAndCaseAreSet(): void
    {
        $params = [
            'cube' => [
                'algorithm' => 'R',
                'case' => 'R',
            ],
        ];

        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                sprintf('/cube?%s', http_build_query($params))
            )
        );

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString(htmlentities('You can ony provide a "case" or an "algorithm", but not both.'), (string) $response->getBody());
    }

    public function testWhenRouteNotFound(): void
    {
        $response = $this->getApp()->handle(
            $this->createRequest('GET', '/not-found'),
        );

        $this->assertEquals(404, $response->getStatusCode());
    }
}
