<?php
/* These tests make use of various configuration scenarios. They are managed here. */

use OpenApiSlim4\OpenApiSlim4;

if ($container->get('nativeSlimConfiguration')) {
    require __DIR__ . '/nativeSlimConfiguration.php';
    slim4ConfigureRoutes($app);
#    slim4ConfigureGroupMiddleware($app);  // Future Development
    slim4ConfigureGlobalMiddleware($app);
} else {
    $openApiConfigurator = new OpenApiSlim4('/var/www/config/openapi.yml', $app);
    $openApiConfigurator->configureFramework();
}

# Testing scenarios and variables
# DI in constructor or through setters
# Mocking to test Exceptions
# JSON YAML
# Good and bad formatting