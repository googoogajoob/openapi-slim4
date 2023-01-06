# Purpose 
The purpose of the tests is to verify that a Slim4 application can be dynamically created so that it correctly implements the parameters defined in an openapi definition. This involves three aspects of the openapi definition:
The focus of the tests is not the actual json response but the fact that the correct handler and/or middleware has been called.
* The Path/Route
* The Http Method
* The request Handler
## Supplemental possibilities
* In addition, as a fourth aspect, **OpenApiSlim4** can define middleware handlers in Slim4 
* This is possible at two levels:
  * Global Middleware
  * Specific path/route middleware

# Development and Testing
This project is intended to be one of many vendor applications included via composer. In and of itself it cannot accomplish much. In order to perform tests, a realistic environment needs to exist. Therefore, a Docker testing environment has been created. The Docker container represents a project which includes OpenApiSlim4 and Slim4 as members of its vendor array. 
* The Tests function at two different levels
  * Within the docker test container, codeception tests have been written which test various aspects of the application given certain pre-conditions
    * Via an environment variable different versions of PHP can be tested
      * Variable Name: PHP_VERSION
      * Possible Values: 7.4, 8.0, 8.1
    * Via an environment variable the openapi format in json or yaml can be tested
      * OPENAPI_FORMAT
      * Possible Values: yaml, json (case unsensitive). Also, yaml and yml refer to a yaml format 
    * **ToDo:** other stuff
    * **ToDo:** Give examples of the scripts
  * Outside the docker container, i.e. from the project itself, scripts have been written to test the project in various scenarios
    * **ToDo:** Give examples of the scripts

--- **ToDo:** Development stuff follows
I need ENV-Options for
* OpenApi File Name
* Exceptions On/Off
* Logging On/Off