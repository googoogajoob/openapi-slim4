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

$settings = [
    'testSettings-1' => 'This is Test Setting 1',
    'testSettings-2' => 'This is Test Setting 2',
    'displayErrorDetails' => true,
    'logError' => false,
    'logErrorDetails' => false
];
$containerBuilder->addDefinitions($settings);

// Set up dependencies
#$dependencies = require __DIR__ . '/../app/dependencies.php';
#$dependencies($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

if (getenv('NATIVE_SLIM_CONFIG')) {

} else {
    $openApiConfigurator = new OpenApiSlim4(__DIR__ . '/../config/openapi.yml', $app);
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