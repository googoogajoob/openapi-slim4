<?php

declare(strict_types=1);

namespace Testserver\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

abstract class BaseMiddleware implements Middleware
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
    protected function doProcess(Request $request, RequestHandler $handler, string $message): Response
    {
#        $body = json_decode((string) $request->getBody(), true);
#        if (!array_key_exists('message', $body)) {
#            $body['message'] = null;
#        }
#        $body['message'][] = $message;
#        $response->getBody()->write(json_encode($payload));

        return $handler->handle($request);
    }
}