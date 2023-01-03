# Purpose
Automatically define the path routing of a slim4 application from an Openapi definition.

**This Includes**
* Path/Route HTTP-Methods and Handlers
* Path/Route Middleware
* Global Middleware

# Installation

# Usage
## Requirements
* Php 8.1
* Slim4
* An OpenApi Definition
## Parameter Definitions and Dependency Injection Options 
|                            | Constructor | Setter | Environment Variable | Required | Remarks                                                 |
|----------------------------|:----------:|:------:|:--------------------:|:----:|---------------------------------------------------------|
| Slim4 App                  | ✅           |   ✅    |          ❌           |✅|                                                         | 
| Openapi Definition         | ✅           |   ✅    |          ❌           |✅| Filename option via Environment Variable                |
| Logging                    | ✅           |   ✅    |          ❔           |❌| Environment Variable Flag. Default false (no logging)   |
| Throw Validation Exception | ✅           |   ✅    |          ❔           |❌| Environment Variable Flag. Default false (no exception) |

# Development and Testing
For testing details see [tests/README.md](./tests/README.md)
