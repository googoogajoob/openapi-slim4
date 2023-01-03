# Development and Testing
* A Docker Container is used for development.
* The Docker Container is part of the test architecture: _tests/docker-test-environment/docker-compose.yml_

I need ENV-Options for
* OpenApi File Name
* Exceptions On/Off
* Logging On/Off

* The actual tests are performed as codeception tests within the docker-test-environment
* The shell scripts in this directory execute tests from within the docker container