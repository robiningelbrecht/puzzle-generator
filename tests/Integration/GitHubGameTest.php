<?php

namespace App\Tests\Integration;

use App\Tests\SvgDriver;
use App\Tests\WebTestCase;
use Spatie\Snapshots\MatchesSnapshots;
use Zadorin\Airtable\Query\SelectQuery;
use Zadorin\Airtable\Record;
use Zadorin\Airtable\Recordset;

class GitHubGameTest extends WebTestCase
{
    use MatchesSnapshots;

    public function testRenderCube(): void
    {
        $selectQuery = $this->createMock(SelectQuery::class);

        $this->airtableClient
            ->expects($this->once())
            ->method('table')
            ->willReturn($this->airtableClient);

        $this->airtableClient
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectQuery);

        $selectQuery
            ->expects($this->once())
            ->method('limit')
            ->willReturn($selectQuery);

        $selectQuery
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Recordset([new Record(['Name' => 'R'])]));

        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                '/github-game/cube'
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertMatchesSnapshot((string) $response->getBody(), new SvgDriver());
    }

    public function testDoTurn(): void
    {
        $selectQuery = $this->createMock(SelectQuery::class);

        $this->airtableClient
            ->expects($this->exactly(2))
            ->method('table')
            ->willReturn($this->airtableClient);

        $this->airtableClient
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectQuery);

        $selectQuery
            ->expects($this->once())
            ->method('limit')
            ->willReturn($selectQuery);

        $record = new Record(['Name' => 'R']);
        $selectQuery
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Recordset([$record]));

        $this->airtableClient
            ->expects($this->once())
            ->method('update');

        $response = $this->getApp()->handle(
            $this->createRequest(
                'GET',
                '/github-game/turn/D'
            )
        );

        $this->assertEquals('R D', $record->getFields()['Name']);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertMatchesJsonSnapshot($response->getHeaders());
    }
}
