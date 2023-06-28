# Purpose
Configure the paths of a slim4 application from an **openapi** definition.

[![Total Downloads](https://img.shields.io/packagist/dt/monolog/monolog.svg)](https://packagist.org/packages/googoogajoob/openapi-slim4)

# Installation
* composer require googoogajoob/openapi-slim4

# Requirements
* Php 8.0
* Slim4
* An OpenApi Definition (yaml or json)

# Usage
There are many examples of how one can configure the routes of a slim4 application. One such [skeleton project](https://github.com/slimphp/Slim-Skeleton) uses [index.php](https://github.com/slimphp/Slim-Skeleton/blob/master/public/index.php) and [routes.php](https://github.com/slimphp/Slim-Skeleton/blob/master/app/routes.php) to accomplish this.

The test cases for openapi-slim4 have adapted this in [index.php](./tests/docker-test-environment/public/index.php) where openapi-slim4 replaces the need to call _routes.php_.

The critical section is
```php
/* BEGIN ROUTE AND MIDDLEWARE CONFIGURATION
When using OpenApiSlim4, only the ELSE branch would be needed */
if ($container->get('nativeSlimConfiguration')) {
require __DIR__ . '/../config/nativeSlimConfiguration.php';
slim4ConfigureRoutes($app);
#    slim4ConfigureGroupMiddleware($app);  // Future Development
    slim4ConfigureGlobalMiddleware($app);
} else {
    $openApiConfigurator = new OpenApiSlim4($container->get('openApiPath'), $app, $logger, $throwExceptionOnInvalid);
    if (!$openApiConfigurator->configureFramework()) {
       throw new Exception($openApiConfigurator->getValidationMessagesString());
    }
}
/* END ROUTE AND MIDDLEWARE CONFIGURATION */
```
Assuming all the variables have been correctly defined the absolute minimum would be the 2 lines
```php
$openApiConfigurator = new OpenApiSlim4($container->get('openApiPath'), $app, $logger, $throwExceptionOnInvalid);
$openApiConfigurator->configureFramework();
```
If problems occur, please review the tests

# Description
## Preface
```
With REST-API definitions there is a difference of opinion about terminology.
In particular with the terms PATH and ROUTE.

For example when referencing the REST-API endpoint GET /user/data/{id}
* Slim4 refers to "/user/data" as a ROUTE
* Openapi refers to "/user/data" as a PATH

The documentation in this project uses the two terms interchangeably 
```
## Concept
openapi-slim4 is a tool that can read an openapi specification and dynamically configure a slim4 application accordingly. This includes endpoint handlers as well as middleware.
openapi-slim4 will typically be called in the _index.php_ entry point of an application and dynamically configure slim4 upon every request. 
This effectively eliminates the need to configure the restapi endpoints via other php code such as _routes.php_ for example. 

Thus, when an openapi definition changes, the route specifications of the slim4 application will automatically be adjusted.
Depending on how trivial the changes to the specification are, this may or may not have larger consequences for the handler and middleware codebase.

In essence openapi-slim4 performs the following:
* Openapi endpoint definitions are mapped to handlers
* Global middleware defined in openapi is configured as global middleware in slim4
* Configuring PATH middleware is planned for future development 

_An openapi definition does not allow for the specification of middleware. However, openapi-slim4 implements an extension to the standard openapi syntax which allows for this._

### The 'source-of-truth' argument
In discussions surrounding the implementation of openapi in the development of restapis, the _source-of-truth_ argument often comes into play.
Central to this discussion is the question, "what role does the openapi definition play in the larger software architecture? 
Is it simply a means of documentation or is it the controlling instance of how a restapi service actually operates?"   
#### Documentation Only
This viewpoint sees an openapi specification simply as a means of documentation. The actual 'source of truth' is the code that creates the restapi service (a php slim4 application or any number of other possibilities).
In such scenarios there are many generators available which can dynamically create an openapi documentation from a specific codebase.
This may be the best option for smaller development teams, where only a few clients are dependent on the restapi service and communication between developers is uncomplicated.  
#### Controlling instance
This viewpoint sees an openapi specification not only as documentation but also as the document which ultimately defines how a restapi service WILL actually operate. The openapi specification is not only documentation but also a configuration file.
In this sense the openapi definition is a kind of contract, to which all participants MUST conform.
This may make more sense for larger development teams. A smaller architectural team creates the definition and all developers hold to it, thus reducing the need for communication among developers.

>openapi-slim4 was conceived to support the 'controlling instance' type of operation

## Specific Capabilities
* HTTP-Method Handlers
* Path Middleware (Future development)
* Global Middleware

## Behavior
* Paths and Middleware will be set in accordance with the Openapi definition (see table below)
* Optional logging via a _Psr\Log\LoggerInterface_ 
* Optionally throw an Exception upon validation failure 

## Openapi - Slim4 Mapping
The parameters necessary for configuring Slim4 are derived from the Openapi definition. The Slim4-method for performing the configuration has three parameters. This table shows where they are taken from in the openapi definition.
```php
RouteCollectorProxy::map(array $methods, string $pattern, $callable): RouteInterface
```

| Openapi Parameter                         | Slim4 Parameter | Remarks                                                                                                                                                                                                                           |
|:----|------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| paths.\<path\>.\<operation\>              | $methods         | One HTTP Method                                                                                                                                                                                                                   |
| paths.\<path\>                            | $pattern         | The URL Path/Route                                                                                                                                                                                                                |
| paths.\<path\>.\<operation\>.operationId  | $callable        | The PHP callable can have two forms \<class\>:method or \<class\><br>- In the first case the separator ":" and **NOT** "::" is expected<br>- In the second case the method _\_\_invoke()_ is expected to be a member of the class |

## Parameter Definitions and Dependency Injection Options 
The following table summarizes, the possibilities of supplying the settings for the **openapi-slim4** object

|                            | Constructor |                Setter                 |        Environment Variable         | Required | Default | Remarks                                                                                                             |
|----------------------------|:----------:|:-------------------------------------:|:-----------------------------------:|:----:|----|---------------------------------------------------------------------------------------------------------------------|
| Openapi Definition         | ✅           |     ✅<br>OpenApiSlim4::setOpenApi     | **OPENAPI_PATH**<br>_Filename only_ |✅|None| The Openapi definition can be specified as an object of cebe/php-openapi/src/Reader or a filename (JSON, YAML, YML) |
| Slim4 App                  | ✅           | ✅<br>OpenApiSlim4::setSlimApplication |                  ❌                  |✅|None| Set the Slim4 **app** Object                                                                                        | 
| Logging                    | ✅           |                   ✅                   |                  ❌                  |❌|False - no logging| Environment Variable Flag. Default false (no logging)                                                               |
| Throw Validation Exception | ✅           |                   ✅                   |                  ❌                  |❌|False - exceptions not thrown| Environment Variable Flag. Default false (no exception)                                                             |

# Development and Testing
For testing details see [tests/README.md](./tests/README.md)
