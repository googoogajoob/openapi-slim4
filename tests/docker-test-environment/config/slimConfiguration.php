<?php

declare(strict_types=1);

use Slim\App;

function slim4ConfigureRoutes(App $slimApp) {
    $slimApp->map(['GET', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD', 'TRACE'], '/foo', 'Testserver\Handlers\InvokeHandler');
    $slimApp->map(['POST'], '/foo', 'Testserver\Handlers\InvokeHandler')
            ->add('Testserver\Middleware\outgoing\OutgoingMiddleware3');
    $slimApp->map(['GET', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD', 'TRACE'], '/bar', 'Testserver\Handlers\InvokeHandler');
    $slimApp->map(['POST'], '/bar', 'Testserver\Handlers\InvokeHandler')
            ->add('Testserver\Middleware\outgoing\OutgoingMiddleware1')
            ->add('Testserver\Middleware\outgoing\OutgoingMiddleware2');
}

/** Future Development
function slim4ConfigureGroupMiddleware(App $slimApp) {
    $junk = $slimApp->getRouteCollector();
    $junk = $slimApp->getMiddlewareDispatcher();
}**/

function slim4ConfigureGlobalMiddleware(App $slimApp) {
    $slimApp->add('Testserver\Middleware\outgoing\OutgoingMiddleware4')
            ->add('Testserver\Middleware\outgoing\OutgoingMiddleware5')
            ->add('Testserver\Middleware\incoming\IncomingMiddleware1')
            ->add('Testserver\Middleware\incoming\IncomingMiddleware2');
}