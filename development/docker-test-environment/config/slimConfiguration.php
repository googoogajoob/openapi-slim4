<?php

declare(strict_types=1);

use Slim\App;

function slim4ConfigureRoutes(App $slimApp) {
    $slimApp->map(['GET', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD', 'TRACE'], '/foo', 'Testserver\Handlers\InvokeHandler');
    $slimApp->map(['POST'], '/foo', 'Testserver\Handlers\InvokeHandler')
            ->add('Testserver\Middleware\PostMiddleware2');
    $slimApp->map(['GET', 'PUT', 'PATCH', 'DELETE', 'OPTIONS', 'HEAD', 'TRACE'], '/bar', 'Testserver\Handlers\InvokeHandler');
    $slimApp->map(['POST'], '/bar', 'Testserver\Handlers\InvokeHandler')
            ->add('Testserver\Middleware\PostMiddleware1')
            ->add('Testserver\Middleware\PostMiddleware2');
}

/** Future Development
function slim4ConfigureGroupMiddleware(App $slimApp) {
    $junk = $slimApp->getRouteCollector();
    $junk = $slimApp->getMiddlewareDispatcher();
}**/

function slim4ConfigureGlobalMiddleware(App $slimApp) {
    $slimApp->add('Testserver\Middleware\GlobalMiddleware1')
            ->add('Testserver\Middleware\GlobalMiddleware2')
            ->add('Testserver\Middleware\GlobalMiddleware3');
}