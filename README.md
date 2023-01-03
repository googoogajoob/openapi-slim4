# Purpose
Automatically define the path routing of a slim4 application from an Openapi definition.

**This Includes**
* Path/Route HTTP-Methods and Handlers
* Path/Route Middleware
* Global Middleware

# Installation

# Behavior
* Slim4 is required
* Paths and Middleware will be set in accordance with the Openapi definition (see table below)
* Optional logging via a _Psr\Log\LoggerInterface_ 
* Optionally throw an Exception upon validation failure 
## Openapi - Slim4 Mapping
| Openapi Parameter | Slim4 Option | Remarks |
| ----- | ----- | ----- |
| dude.sub-dude| slim-dude |  |
# Usage
## Requirements
* Php 8.1
* Slim4
* An OpenApi Definition
## Parameter Definitions and Dependency Injection Options 
|                            | Constructor | Setter | Environment Variable | Required | Default | Remarks|
|----------------------------|:----------:|:------:|:--------------------:|:----:|----|--------------------------------------------------------------|
| Slim4 App                  | ✅           |   ✅    |          ❌           |✅|None|                                                                  | 
| Openapi Definition         | ✅           |   ✅    |    Filename only     |✅|None| The Openapi definition can be specified as an object or filename |
| Logging                    | ✅           |   ✅    |          ❔           |❌|False - no logging| Environment Variable Flag. Default false (no logging)            |
| Throw Validation Exception | ✅           |   ✅    |          ❔           |❌|False - exceptions not thrown|Environment Variable Flag. Default false (no exception)          |
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

For constructor and setter specifications see [src/OpenApiSlim4.php](./src/OpenApiSlim4.php) 
# Development and Testing
For testing details see [tests/README.md](./tests/README.md)
