<?php

declare(strict_types=1);

namespace Testserver\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class GlobalMiddleware3 implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getBody();
        $body = array_merge($body, ['message' => __CLASS__]);
        $request = $request->withBody($body);

        return $handler->handle($request);
    }
}