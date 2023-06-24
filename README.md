# Purpose
Configure the paths of a slim4 application from an **openapi** definition.

[![Total Downloads](https://img.shields.io/packagist/dt/monolog/monolog.svg)](https://packagist.org/packages/googoogajoob/openapislim4)

# Installation
* composer require googoogajoob/openapislim4

# Requirements
* Php 8.0
* Slim4
* An OpenApi Definition (yaml or json)

# Usage

## Preface
```
With REST-API definitions there is a difference of opinion about terminology. In particular with the terms PATH and ROUTE.

For example when referencing the REST-API endpoint GET /user/data/{id}
* Slim4 refers to "/user/data" as a ROUTE
* Openapi refers to "/user/data" as a PATH

The documentation in this project uses the two terms interchangeably 
```

## Specific Capabilities
* HTTP-Method Handlers
* Path Middleware
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
The following table summarizes, the possibilities of supplying the settings for the **OpenApiSlim4** object

|                            | Constructor |                Setter                 |  Environment Variable   | Required | Default | Remarks                                                                                                             |
|----------------------------|:----------:|:-------------------------------------:|:-----------------------:|:----:|----|---------------------------------------------------------------------------------------------------------------------|
| Openapi Definition         | ✅           |     ✅<br>OpenApiSlim4::setOpenApi     | Filename only |✅|None| The Openapi definition can be specified as an object of cebe/php-openapi/src/Reader or a filename (JSON, YAML, YML) |
| Slim4 App                  | ✅           | ✅<br>OpenApiSlim4::setSlimApplication |            ❌            |✅|None| Set the Slim4 **app** Object                                                                                        | 
| Logging                    | ✅           |                   ✅                   |            ❌            |❌|False - no logging| Environment Variable Flag. Default false (no logging)                                                               |
| Throw Validation Exception | ✅           |                   ✅                   |            ❌            |❌|False - exceptions not thrown| Environment Variable Flag. Default false (no exception)                                                             |

<!-- 
### Priorities
* Environment Variables
* Constructor
* Setters
## Examples
### Constructor
### Setters
### Environment Variables
### Mixed settings
### Minimalistic Parameters
### All Parameters

**ToDo:** (is this the right place? maybe it should be up a level or two, wo that it is near the table) For constructor and setter specifications see [src/OpenApiSlim4.php](./src/OpenApiSlim4.php) 
# Development and Testing
For testing details see [tests/README.md](./tests/README.md)
-->