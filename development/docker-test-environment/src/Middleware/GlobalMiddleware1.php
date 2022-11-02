<?php

declare(strict_types=1);

namespace Testserver\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class GlobalMiddleware1 extends BaseMiddleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        return $this->doProcess($request, $handler, __CLASS__);
    }
}