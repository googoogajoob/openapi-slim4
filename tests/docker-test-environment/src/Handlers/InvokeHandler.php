<?php
declare(strict_types=1);

namespace Testserver\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InvokeHandler extends BaseHandler
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        return $this->addMessage($request, $response, $args, __METHOD__);
    }
}