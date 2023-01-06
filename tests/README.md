# Purpose 
The purpose of the tests is to verify that a Slim4 application can be dynamically created so that it correctly implements the parameters defined in an openapi definition. This involves three main aspects of the openapi definition:
* The Path/Route
* The Http Method
* The request Handler
## Supplemental possibilities
* In addition, as a fourth aspect, **OpenApiSlim4** can define middleware handlers in Slim4 
* This is possible at two levels:
  * Global Middleware
  * Specific path/route middleware

The focus of the tests is not the actual json response but the fact that the correct handler and/or middleware has been called. 

# Development and Testing
This project is intended to be one of many vendor applications included via composer. In and of itself it cannot accomplish much. In order to perform tests, a realistic environment needs to exist. Therefore, a Docker testing environment has been created. The Docker container represents a project which includes OpenApiSlim4 and Slim4 as members of its vendor array. 
* The Tests function at two different levels
  * Within the docker test container, codeception tests have been written which test various aspects of the application given certain pre-conditions
    * **ToDo:** PHP Version
    * **ToDo:** Json or Yaml
    * **ToDo:** other stuff
    * **ToDo:** Give examples of the scripts
  * Outside the docker container, i.e. from the project itself, scripts have been written to test the project under various conditions
    * **ToDo:** Give examples of the scripts

--- **ToDo:** Development stuff follows
I need ENV-Options for
* OpenApi File Name
* Exceptions On/Off
* Logging On/Off