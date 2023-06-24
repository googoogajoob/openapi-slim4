<?php

declare(strict_types=1);

namespace Testserver\Middleware\outgoing;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

abstract class BaseOutgoingMiddleware implements Middleware
{
    abstract public function process(Request $request, RequestHandler $handler): Response;

    /**
     * Add Message to the response (assumes json format)
     *
     * @param Request $request
     * @param RequestHandler $handler
     * @param string $message
     * @return Response
     */
    protected function addMessage(Request $request, RequestHandler $handler, string $message): Response
    {
        $response = $handler->handle($request);
        $body = json_decode((string) $response->getBody(), true);
        $body['middleware']['outgoing'][] = $message;
        $response->getBody()->rewind();
        $response->getBody()->write(json_encode($body));

        return $response;
    }
}