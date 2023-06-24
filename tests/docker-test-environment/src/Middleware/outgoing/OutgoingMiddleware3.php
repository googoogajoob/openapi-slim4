<?php

declare(strict_types=1);

namespace Testserver\Middleware\outgoing;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class OutgoingMiddleware3 extends BaseOutgoingMiddleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        return $this->addMessage($request, $handler, substr(__CLASS__, strlen(__NAMESPACE__) + 1));
    }
}