<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Handlers\ErrorHandler;
use Slim\ResponseEmitter;
use OpenApiSlim4\OpenApiSlim4;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Setup Settings
/** @var array $settings */
require __DIR__ . '/../config/settings.php';
$containerBuilder->addDefinitions($settings);

// Setup dependencies
/** @var array $dependencies */
require __DIR__ . '/../config/dependencies.php';
$containerBuilder->addDefinitions($dependencies);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

if ($container->get('nativeSlimConfiguration')) {
    $junk = 1;
} else {
    $openApiConfigurator = new OpenApiSlim4($app);
    $openApiConfigurator->configureSlimFramework();
}

$displayErrorDetails = $container->get('displayErrorDetails');
$logError = $container->get('logError');
$logErrorDetails = $container->get('logErrorDetails');

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new ErrorHandler($callableResolver, $responseFactory);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Body Parsing Middleware
$app->addBodyParsingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);