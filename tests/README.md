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
    * Via an environment variable a file can be named which contains an openapi definition (yaml and json format)
      * Possible values via the file extension: yaml|yml, json (case insensitive)
    * Other Parameters 
      * **SLIM**: (equals 'SLIM') Define the Slim4 application through conventional php means. Needed, in order to verify this configurator
      * **JSON**: (Equals 'JSON') Assume json format for the openapi definition
      * **CLEAN**: (Equals 'CLEAN') Clean/delete the previous test results
  * The script [run-all-tests.sh](./run-all-tests.sh) uses these parameters to perform its tests

--- **ToDo:** Development stuff follows
I need ENV-Options for
* OpenApi File Name
* Exceptions On/Off
* Logging On/Off