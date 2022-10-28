<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function Safe\file_get_contents;

use Twig\Environment;

class DocsRequestHandler
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('docs.html.twig', [
            'title' => 'Puzzle generator',
            'content' => (new \Parsedown())->text(file_get_contents(dirname(__DIR__, 2).'/README.md')),
        ]));

        return $response;
    }
}
