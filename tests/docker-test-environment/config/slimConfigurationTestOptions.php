<?php
/**
 * The tests make use of various configuration scenarios. They are managed here.
 */

use DI\Container;
use Slim\App;
use OpenApiSlim4\OpenApiSlim4;

/**
 * Define local variable types (mainly for the IDE)
 *
 * @var Container $container
 * @var App $app
 */
if ($container->get('nativeSlimConfiguration')) {
    require __DIR__ . '/nativeSlimConfiguration.php';
    slim4ConfigureRoutes($app);
#    slim4ConfigureGroupMiddleware($app);  // Future Development
    slim4ConfigureGlobalMiddleware($app);
} else {
    $openApiConfigurator = new OpenApiSlim4($container->get('openApiPath'), $app);
    $openApiConfigurator->configureFramework();
}