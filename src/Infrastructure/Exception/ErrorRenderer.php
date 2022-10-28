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
            if ($displayErrorDetails) {
                $html = '<p>The application could not run because of the following error:</p>';
                $html .= sprintf('<h2>%s</h2>', htmlentities($exception->getMessage()));
            } else {
                $html = "<p>{$this->getErrorDescription($exception)}</p>";
            }

            return $this->twig->render('error.html.twig', [
                'title' => $this->getErrorTitle($exception),
                'content' => $html,
            ]);
        }

        return parent::__invoke($exception, $displayErrorDetails);
    }
}
