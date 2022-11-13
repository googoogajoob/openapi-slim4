<?php

declare(strict_types=1);

namespace Testserver\Middleware\incoming;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

abstract class BaseIncomingMiddleware implements Middleware
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
        $incomingMessages =$request->getAttribute('incoming', []);
        $incomingMessages[] = $message;
        $request = $request->withAttribute('incoming', $incomingMessages);

        return $handler->handle($request);
    }
}