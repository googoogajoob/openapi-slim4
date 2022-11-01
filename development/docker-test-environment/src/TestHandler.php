<?php
declare(strict_types=1);

namespace TestServer;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TestHandler
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $returnStatus = 501;
        $payload = ['data' => 'Test Response'];
        $response = $response->withHeader('Content-type', 'application/json');
        $response = $response->withStatus($returnStatus);
        $response->getBody()->write(json_encode($payload));

        return $response;
    }
}