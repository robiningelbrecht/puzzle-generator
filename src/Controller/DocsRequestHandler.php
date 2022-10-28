<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function Safe\file_get_contents;

class DocsRequestHandler
{
    public function handle(
        ServerRequestInterface $request,
        ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write((new \Parsedown())->text(
            file_get_contents(dirname(__DIR__, 2).'/README.md')
        ));

        return $response;
    }
}
