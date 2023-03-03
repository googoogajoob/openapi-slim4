<?php

declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Handlers\ErrorHandler;
use Dotenv\Dotenv;
use OpenApiSlim4\OpenApiSlim4;
use OpenApiSlim4\OpenApiSlim4ShutdownHandler;

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

/**
 * Define local variable types (mainly for the IDE)
 *
 * @var Container $container
 * @var array $settings
 * @var array $dependencies
 */
$containerBuilder = new ContainerBuilder();
require __DIR__ . '/../config/settings.php';
$containerBuilder->addDefinitions($settings); // Add additional settings
require __DIR__ . '/../config/dependencies.php';
$containerBuilder->addDefinitions($dependencies); // Add dependencies
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

$displayErrorDetails = $container->get('displayErrorDetails');
$logError = $container->get('logError');
$logErrorDetails = $container->get('logErrorDetails');
$responseFactory = $app->getResponseFactory();
$errorHandler = new ErrorHandler($callableResolver, $responseFactory);

$shutdownHandler = new OpenApiSlim4ShutdownHandler($errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

/* BEGIN ROUTE AND MIDDLEWARE CONFIGURATION
   When using OpenApiSlim4 only the ELSE branch would be needed */
if ($container->get('nativeSlimConfiguration')) {
    require __DIR__ . '/../config/nativeSlimConfiguration.php';
    slim4ConfigureRoutes($app);
#    slim4ConfigureGroupMiddleware($app);  // Future Development
    slim4ConfigureGlobalMiddleware($app);
} else {
    $openApiConfigurator = new OpenApiSlim4($container->get('openApiPath'), $app);
    $openApiConfigurator->configureFramework();
    $junk = $a;
}
/* END ROUTE AND MIDDLEWARE CONFIGURATION */

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$app->run();