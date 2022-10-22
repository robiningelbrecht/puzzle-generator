<?php

namespace App\Infrastructure;

use Slim\Error\AbstractErrorRenderer;

class ErrorRenderer extends AbstractErrorRenderer
{
    protected $defaultErrorTitle = 'Waw, this is embarrassing';

    public function __invoke(\Throwable $exception, bool $displayErrorDetails): string
    {
        if ($displayErrorDetails) {
            $html = '<p>The application could not run because of the following error:</p>';
            $html .= sprintf('<h2>%s</h2>', htmlentities($exception->getMessage()));
        } else {
            $html = "<p>{$this->getErrorDescription($exception)}</p>";
        }

        return $this->renderHtmlBody($this->getErrorTitle($exception), $html);
    }

    public function renderHtmlBody(string $title = '', string $html = ''): string
    {
        return sprintf(
            '<!doctype html>'.
            '<html lang="en">'.
            '    <head>'.
            '        <meta charset="utf-8">'.
            '        <meta name="viewport" content="width=device-width, initial-scale=1">'.
            '        <title>%s</title>'.
            '        <style>'.
            '            body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif}'.
            '            h1{margin:0;font-size:48px;font-weight:normal;line-height:48px}'.
            '            strong{display:inline-block;width:65px}'.
            '        </style>'.
            '    </head>'.
            '    <body>'.
            '        <h1>%s</h1>'.
            '        <div>%s</div>'.
            '    </body>'.
            '</html>',
            $title,
            $title,
            $html
        );
    }
}