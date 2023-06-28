# Purpose 
Provide the possibility to test various scenarios in the test environment. The test environment is designed to represent the essential aspects of an application which uses Slim4 and **OpenApiSlim4**.  

## Routes
The purpose of the tests is to verify that a Slim4 application can be dynamically configured so that it correctly implements the parameters defined in an openapi definition.
This involves three aspects of the openapi definition:
* The path/route
* The http method
* The request handler 

The focus of the tests is not the actual json response but the fact that the correct handler has been called

## Middleware possibilities
* In addition, as a fourth aspect, **openapi-slim4** can define middleware handlers 
* This is possible at two levels:
  1. Global Middleware
  2. Specific path/route middleware (Future development)

In a similar fashion to the main handler tests, these tests are not interested in the content and/or function of the middleware but rather the fact that the middleware has been correctly called in accordance with the openapi definition 

# Development and Testing
This project is intended to be one of many vendor applications included via composer. In order to perform tests, a realistic environment needs to exist.
Therefore, a Docker test environment has been created (subdirectory _docker-test-environment_). 
The docker test container represents a project which includes **openapi-slim4** and Slim4 as a members of the vendor array. 
* To start the testing environment (i.e. container) start docker-compose with [./docker-test-environment/docker-compose.yml](./docker-test-environment/docker-compose.yml)
* The tests function at two different levels:
  * From the main project
    * The shell script [./run-all-tests.sh](./run-all-tests.sh) runs all tests, in all scenarios, in the docker test environment 
  * In the docker test environment
      * The shell script [./docker-test-environment/tests/run-success-test.sh](./docker-test-environment/tests/run-success-test.sh) runs a series of codeception tests for the specific environment. The expectation is that the route and middleware handlers are correctly called.
      * The shell script [./docker-test-environment/tests/run-error-test.sh](./docker-test-environment/tests/run-error-test.sh) runs a series of codeception tests based on the specified environment. The expectation is that all errors are handled correctly.