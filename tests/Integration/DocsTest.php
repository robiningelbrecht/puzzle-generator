<?php

namespace App\Tests\Integration;

use App\Tests\WebTestCase;

class DocsTest extends WebTestCase
{
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
