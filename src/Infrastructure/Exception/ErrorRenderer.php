<?php

namespace App\Infrastructure\Exception;

use Slim\Error\Renderers\HtmlErrorRenderer;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment;

class ErrorRenderer extends HtmlErrorRenderer
{
    protected $defaultErrorTitle = 'Waw, this is embarrassing';

    public function __construct(
        private readonly Environment $twig
    ) {
    }

    public function __invoke(\Throwable $exception, bool $displayErrorDetails): string
    {
        if ($exception instanceof PuzzleException || $exception instanceof HttpNotFoundException) {
            $title = match (true) {
                $exception instanceof HttpNotFoundException => 'Lost your way?',
                default => $this->getErrorTitle($exception)
            };
            $content = match (true) {
                $exception instanceof HttpNotFoundException => "Sorry, we can't find that page...",
                default => $exception->getMessage()
            };

            return $this->twig->render('error.html.twig', [
                'title' => $title,
                'content' => $content,
            ]);
        }

        return parent::__invoke($exception, $displayErrorDetails);
    }
}
