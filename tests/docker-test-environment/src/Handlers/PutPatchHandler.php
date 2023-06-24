<?php
declare(strict_types=1);

namespace Testserver\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PutPatchHandler extends BaseHandler
{
    public function put(Request $request, Response $response, $args): Response
    {
        return $this->addMessage($request, $response, $args, __METHOD__);
    }

    public function patch(Request $request, Response $response, $args): Response
    {
        return $this->addMessage($request, $response, $args, __METHOD__);
    }
}