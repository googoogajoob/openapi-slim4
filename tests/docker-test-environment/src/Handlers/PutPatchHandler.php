<?php
declare(strict_types=1);

namespace Testserver\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PutPatchHandler
{
    public function put(Request $request, Response $response, $args): Response
    {
        $returnStatus = 501;
        $attributes = $request->getAttribute('incoming', []);
        $payload = ['data' => 'PUT handler', ['middleware']['incoming'] => $attributes];
        $response = $response->withHeader('Content-type', 'application/json');
        $response = $response->withStatus($returnStatus);
        $response->getBody()->write(json_encode($payload));

        return $response;
    }

    public function patch(Request $request, Response $response, $args): Response
    {
        $returnStatus = 501;
        $attributes = $request->getAttribute('incoming', []);
        $payload = ['data' => 'PATCH handler', ['middleware']['incoming'] => $attributes];
        $response = $response->withHeader('Content-type', 'application/json');
        $response = $response->withStatus($returnStatus);
        $response->getBody()->write(json_encode($payload));

        return $response;
    }
}