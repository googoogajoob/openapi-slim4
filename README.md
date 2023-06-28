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


# Description
## Preface
```
With REST-API definitions there is a difference of opinion about terminology. In particular with the terms PATH and ROUTE.

For example when referencing the REST-API endpoint GET /user/data/{id}
* Slim4 refers to "/user/data" as a ROUTE
* Openapi refers to "/user/data" as a PATH

The documentation in this project uses the two terms interchangeably 
```
## Concept
openapi-slim4 is a tool that can read an openapi specification and dynamically configure a slim4 application accordingly. This includes endpoint handlers as well as middleware.
openapi-slim4 will typically be called in the _index.php_ entry point of an application and dynamically configure slim4 for every request. 
This effectively eliminates the need to configure the restapi endpoints via php code. 

Thus, when an openapi definition changes the route specifications of the slim4 application will automatically be adjusted.
Depending on how trivial the changes to the specification were, this may or may not have larger consequences for the handler and middleware codebase.

In essence openapi-slim4 performs the following:
* Openapi endpoint definitions are mapped to handlers
* Path middleware defined in openapi is mapped as middleware to the path in slim4
* Global middleware defined in openapi is configured as global middleware in slim4

An openapi definition does not allow for the specification middleware. openapi-slim4 implements an extension to the standard openapi syntax which allows for this.

### The 'source-of-truth' argument
In discussions surrounding the implementation of openapi in the development of RestApis for frontend and backend development, the _source-of-truth_ argument often comes into play.
Central to this discussion is the question of "what role does the openapi play in the software architecture?". Is it simply a means of documentation or is it the controlling instance of how a RestApi service should work?   
#### Documentation Only
This viewpoint sees an openapi specification simply as a means of documentation. The actual 'source of truth' is the code that creates the RestApi service, in this case a php slim4 application. There are certainly a number of other possibilities.
This may be the best option for smaller development teams, where only a few clients a dependent on the RestApi service.  
#### Controlling instance
This viewpoint sees an openapi specification not only as documentation but also as the document which ultimately defines how a RestApi service MUST operate. Any client which wishes to use the service need not have any contact with the developer of the service. They can simply assume that the service will do exactly what is specified in the openapi definition.
In this sense the openapi definition is a kind of contract, to which all participants should conform. This may make more sense for larger development teams.

**openapi-slim4 is constructed to support this type of operation**

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
The following table summarizes, the possibilities of supplying the settings for the **openapi-slim4** object

|                            | Constructor |                Setter                 |  Environment Variable   | Required | Default | Remarks                                                                                                             |
|----------------------------|:----------:|:-------------------------------------:|:-----------------------:|:----:|----|---------------------------------------------------------------------------------------------------------------------|
| Openapi Definition         | ✅           |     ✅<br>openapi-slim4::setOpenApi     | Filename only |✅|None| The Openapi definition can be specified as an object of cebe/php-openapi/src/Reader or a filename (JSON, YAML, YML) |
| Slim4 App                  | ✅           | ✅<br>openapi-slim4::setSlimApplication |            ❌            |✅|None| Set the Slim4 **app** Object                                                                                        | 
| Logging                    | ✅           |                   ✅                   |            ❌            |❌|False - no logging| Environment Variable Flag. Default false (no logging)                                                               |
| Throw Validation Exception | ✅           |                   ✅                   |            ❌            |❌|False - exceptions not thrown| Environment Variable Flag. Default false (no exception)                                                             |

# Development and Testing
For testing details see [tests/README.md](./tests/README.md)
