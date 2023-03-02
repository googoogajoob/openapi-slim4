<?php

declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Handlers\ErrorHandler;
use Dotenv\Dotenv;

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

require __DIR__ . '/../config/slimConfigurationTestOptions.php';

$displayErrorDetails = $container->get('displayErrorDetails');
$logError = $container->get('logError');
$logErrorDetails = $container->get('logErrorDetails');
$responseFactory = $app->getResponseFactory();
$errorHandler = new ErrorHandler($callableResolver, $responseFactory);

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$app->run();