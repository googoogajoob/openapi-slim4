<?php
declare(strict_types=1);

namespace Testserver\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BaseHandler
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @param string $message
     * @return Response
     */
    protected function addMessage(Request $request, Response $response, $args, string $message): Response
    {
        $returnStatus = 501;
        $environment = [
            'routing' => (getenv('NATIVE_SLIM_CONFIG') ? 'Native Slim Configuration' : 'OpenApiSlim4 Configuration'),
            'openApiPath' => getenv('OPENAPI_PATH') ? getenv('OPENAPI_PATH') : 'undefined'
        ];
        $attributes = $request->getAttribute('incoming', []);
        $payload = [
            'environment' => $environment,
            'handler' => $message,
            'middleware' => ['incoming' => $attributes]
        ];
        $response = $response->withHeader('Content-type', 'application/json');

        $response = $response->withStatus($returnStatus);
        $response->getBody()->write(json_encode($payload));

        return $response;
    }
}