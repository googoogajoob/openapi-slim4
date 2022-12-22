# Installation
# Purpose 
# Usage
## Requirements
* Php 8.1
* Slim4
* An OpenApi Definition
## Parameters and Options
|           |Constructor|Setter|Environment Variable|
|-----------|----|----|----|
| Slim4 App | ✅ |✅ | |
| Openapi Definition | | |

# Development and Testing
* A Docker Container is used for development.
* The Docker Container is part of the test architecture: _tests/docker-test-environment/docker-compose.yml_

I need ENV-Options for 
* OpenApi File Name
* Exceptions On/Off
* Logging On/Off