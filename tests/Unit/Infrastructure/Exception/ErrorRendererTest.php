<?php

namespace App\Tests\Unit\Infrastructure\Exception;

use App\Infrastructure\Exception\ErrorRenderer;
use App\Infrastructure\Exception\PuzzleException;
use App\Tests\WebTestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Spatie\Snapshots\MatchesSnapshots;
use Twig\Environment;

class ErrorRendererTest extends WebTestCase
{
    use MatchesSnapshots;

    private ErrorRenderer $errorRenderer;

    public function testWithPuzzleException(): void
    {
        $renderer = $this->errorRenderer;
        $this->assertMatchesHtmlSnapshot($renderer(new PuzzleException('Something bad happened'), true));
    }

    public function testWithPuzzleExceptionNoDetails(): void
    {
        $renderer = $this->errorRenderer;
        $this->assertMatchesHtmlSnapshot($renderer(new PuzzleException('Something bad happened'), false));
    }

    public function testWithRuntimeException(): void
    {
        $renderer = $this->errorRenderer;
        $this->assertStringContainsString('<h2>Trace</h2>', $renderer(new \RuntimeException('Something bad happened'), true));
    }

    public function testWithRuntimeExceptionNoDetails(): void
    {
        $renderer = $this->errorRenderer;
        $this->assertStringNotContainsString('<h2>Trace</h2>', $renderer(new \RuntimeException('Something bad happened'), false));
    }

    public function testWithNotFoundException(): void
    {
        $renderer = $this->errorRenderer;
        $this->assertMatchesHtmlSnapshot($renderer(new HttpNotFoundException($this->createMock(ServerRequestInterface::class)), true));
    }

    public function testWithNotFoundExceptionNoDetails(): void
    {
        $renderer = $this->errorRenderer;
        $this->assertMatchesHtmlSnapshot($renderer(new HttpNotFoundException($this->createMock(ServerRequestInterface::class)), false));
    }

    protected function setup(): void
    {
        parent::setup();

        /** @var Environment $twig */
        $twig = $this->getContainer()->get(Environment::class);
        $this->errorRenderer = new ErrorRenderer($twig);
    }
}
