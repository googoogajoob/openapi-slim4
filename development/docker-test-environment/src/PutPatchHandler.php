<?php
declare(strict_types=1);

namespace TestServer;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PutPatchHandler
{
    public function put(Request $request, Response $response, $args): Response
    {
        $returnStatus = 501;
        $payload = ['data' => 'PUT handler'];
        $response = $response->withHeader('Content-type', 'application/json');
        $response = $response->withStatus($returnStatus);
        $response->getBody()->write(json_encode($payload));

        return $response;
    }

    public function patch(Request $request, Response $response, $args): Response
    {
        $returnStatus = 501;
        $payload = ['data' => 'PATCH handler'];
        $response = $response->withHeader('Content-type', 'application/json');
        $response = $response->withStatus($returnStatus);
        $response->getBody()->write(json_encode($payload));

        return $response;
    }
}