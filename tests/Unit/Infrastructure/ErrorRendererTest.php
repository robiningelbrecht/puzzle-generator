<?php

namespace App\Tests\Unit\Infrastructure;

use App\Domain\PuzzleException;
use App\Infrastructure\ErrorRenderer;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Spatie\Snapshots\MatchesSnapshots;

class ErrorRendererTest extends TestCase
{
    use MatchesSnapshots;

    public function testWithPuzzleException(): void
    {
        $renderer = new ErrorRenderer();
        $this->assertMatchesHtmlSnapshot($renderer(new PuzzleException('Something bad happened'), true));
    }

    public function testWithPuzzleExceptionNoDetails(): void
    {
        $renderer = new ErrorRenderer();
        $this->assertMatchesHtmlSnapshot($renderer(new PuzzleException('Something bad happened'), false));
    }

    public function testWithRuntimeException(): void
    {
        $renderer = new ErrorRenderer();
        $this->assertStringContainsString('<h2>Trace</h2>', $renderer(new \RuntimeException('Something bad happened'), true));
    }

    public function testWithRuntimeExceptionNoDetails(): void
    {
        $renderer = new ErrorRenderer();
        $this->assertStringNotContainsString('<h2>Trace</h2>', $renderer(new \RuntimeException('Something bad happened'), false));
    }

    public function testWithNotFoundException(): void
    {
        $renderer = new ErrorRenderer();
        $this->assertMatchesHtmlSnapshot($renderer(new HttpNotFoundException($this->createMock(ServerRequestInterface::class)), true));
    }

    public function testWithNotFoundExceptionNoDetails(): void
    {
        $renderer = new ErrorRenderer();
        $this->assertMatchesHtmlSnapshot($renderer(new HttpNotFoundException($this->createMock(ServerRequestInterface::class)), false));
    }
}
