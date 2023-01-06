# Overview
**ToDo:** Put in cool stuff like Icons etc. 
# Purpose
Automatically configure the paths and routing of a slim4 application from an **openapi** definition.

**This Includes**
* Path/Route HTTP-Methods and Handlers
* Path/Route Middleware
* Global Middleware

# Installation
**ToDo:** composer *
# Behavior
* Slim4 is required
* Paths and Middleware will be set in accordance with the Openapi definition (see table below)
* Optional logging via a _Psr\Log\LoggerInterface_ 
* Optionally throw an Exception upon validation failure 
## Openapi - Slim4 Mapping
| Openapi Parameter                   | Slim4 Option | Remarks |
|-------------------------------------| ----- | ----- |
| **ToDo:** path (need actual syntax) | **ToDo:** slim-dude |  |
| **ToDo:** method(need actual syntax)          | **ToDo:** slim-dude |  |
| **ToDo:** operatorID (need actual syntax)     | **ToDo:** slim-dude |  |
# Usage
## Requirements
* **ToDo:** Php 8.1
* Slim4
* An OpenApi Definition (yaml or json)
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

**ToDo:** (is this the right place? maybe it should be up a level or two, wo that it is near the table) For constructor and setter specifications see [src/OpenApiSlim4.php](./src/OpenApiSlim4.php) 
# Development and Testing
For testing details see [tests/README.md](./tests/README.md)
