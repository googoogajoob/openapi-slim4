# Purpose 
The purpose of the tests is to verify that a Slim4 application can be dynamically created so that it correctly implements the parameters defined in an openapi definition. This involves three aspects of the openapi definition:
* The Path/Route
* The Http Method
* The request Handler 

The focus of the tests is not the actual json response but the fact that the correct handler has been called.

## Middleware possibilities
* In addition, as a fourth aspect, **OpenApiSlim4** can define middleware handlers in Slim4 
* This is possible at two levels:
  1. Global Middleware
  2. Specific path/route middleware

In a similar fashion to the main handler tests, these tests are not interested in the content and/or function of the middleware but rather the fact that the middleware has been correctly called in accordance with the openapi definition. 

# Development and Testing
This project is intended to be one of many vendor applications included via composer. In and of itself it cannot accomplish much. In order to perform tests, a realistic environment needs to exist. Therefore, a Docker testing environment has been created (subdirectory _docker-test-environment_). The docker test container represents a project which includes OpenApiSlim4 and Slim4 as members of its vendor array. 
* The tests function at two different levels:
  * The main project
    * The script [run-all-tests.sh](./run-all-tests.sh) uses parameters to perform tests within the docker test container
    * The script [tests/docker-test-environment/run-codeception-test.sh](tests/docker-test-environment/run-codeception-test.sh) runs a series of codeception test based on the parameter values
  * The docker test container
    * Based on the given parameters a series of codeception tests are performed
## Parameters in the docker test container
| Parameter   | Source               | Value         |
|-------------|----------------------|---------------|
| PHP_VERSION | the container itself | 7.4, 8.0, 8.1 |

  * Via an environment variable a file can be named which contains an openapi definition (yaml and json format)
    * Possible values via the file extension: yaml|yml, json (case insensitive)
  * Other Parameters 
    * **SLIM**: (equals 'SLIM') Define the Slim4 application through conventional php means. Needed, in order to verify this configurator
    * **JSON**: (Equals 'JSON') Assume json format for the openapi definition
    * **CLEAN**: (Equals 'CLEAN') Clean/delete the previous test results

--- **ToDo:** Development stuff follows
I need ENV-Options for
* OpenApi File Name
* Exceptions On/Off
* Logging On/Off