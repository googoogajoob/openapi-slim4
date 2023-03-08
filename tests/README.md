# Purpose 

```
With REST-API definitions there seems to be a difference of opinion about terminology

For example when referencing the REST-API endpoint GET /user/data/{id}

Slim4 refers to "/user/data" as a ROUTE

Openapi refers to "/user/data" as a PATH

The documentation and readme files in this project use the two terms interchangebly 
```

## Routes
The purpose of the tests is to verify that a Slim4 application can be dynamically created so that it correctly implements the parameters defined in an openapi definition. This involves three aspects of the openapi definition:
* The path/route
* The http method
* The request handler 

The focus of the tests is not the actual json response but the fact that the correct handler has been called

## Middleware possibilities
* In addition, as a fourth aspect, **OpenApiSlim4** can define middleware handlers 
* This is possible at two levels:
  1. Global Middleware
  2. Specific path/route middleware

In a similar fashion to the main handler tests, these tests are not interested in the content and/or function of the middleware but rather the fact that the middleware has been correctly called in accordance with the openapi definition 

# Development and Testing
This project is intended to be one of many vendor applications included via composer. In and of itself it cannot accomplish much. In order to perform tests, a realistic environment needs to exist. Therefore, a Docker test environment has been created (subdirectory _docker-test-environment_). The docker test container represents a project which includes OpenApiSlim4 and Slim4 as members of its vendor array. 
* To start the testing environment (i.e. container) start docker-compose with [./docker-test-environment/docker-compose.yml](./docker-test-environment/docker-compose.yml)
* The tests function at two different levels:
  * In the main project
    * The shell script [./run-all-tests.sh](./run-all-tests.sh) uses environment variables and parameters to perform tests for various implementation possibilities
    * The actual tests are performed within the docker test environment 
  * In the docker test environment
      * The shell script [./docker-test-environment/run-success-test.sh](./docker-test-environment/run-success-test.sh) runs a series of codeception tests based on the specified environment variables and parameters

## Variables in the docker test environment
|           | Source main project | Source test environment | Value                             | Remarks                                                                                                                              |
|-----------|---------------------|-------------------------|-----------------------------------|--------------------------------------------------------------------------------------------------------------------------------------|
| CLEAN     | echo variable       | .env file               | defined or undefined              | Delete the codeception test results prior to the test, if defined                                                                    |
| EXCEPTION | echo variable       | .env file               | defined or undefined              | Throw an exception when the openapi definition is invalid, if defined                                                                |
| LOGGING   | echo variable       | .env file               | defined or undefined              | Perform logging, if defined                                                                                                          |
| OPENAPI   | echo variable       | .env file               | Undefined or Filepath + extension | Yaml and Json formats are allowed. Valid file extensions are yaml, yml and json (case insensitive). Has no effect, if SLIM is defined |
| PHP       |                     | the container itself    | 7.4, 8.0, 8.1                     | The Php Version                                                                                                                      |
| SLIM      | echo variable       | .env file               | defined or undefined              | Use a native php script for configuring the routes, if defined. Ignores OPENAPI. Used as a comparison control against OpenSpiSlim4   |

# Evaluating Test Results
//ToDo
Dude, need to explain how the comparison works