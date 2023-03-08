#!/bin/bash

echo "Testing native slim configuration ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-success-test.sh -sc > /dev/null

echo "Testing openapi-slim4 with json format ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-success-test.sh -j > /dev/null

echo "Testing openapi-slim4 with yaml format ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-success-test.sh -y > /dev/null

echo "Testing openapi-slim4 with yml format ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-success-test.sh -Y > /dev/null

echo "Testing openapi-slim4 error handling with validation exception ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-error-test.sh -e > /dev/null

echo "Testing openapi-slim4 error handling with NO validation exception ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-error-test.sh -E > /dev/null

echo
echo "Tests completed (for results see: docker-test-environment/tests/codeception/_output)"
echo
echo "Failed tests contain the word 'fail' in the file name. Here is a list ..."
find docker-test-environment/tests/codeception/_output -name "*fail*"
echo