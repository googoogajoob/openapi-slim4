#!/bin/bash

echo "Testing native slim configuration ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh -sc > /dev/null

echo "Testing openapi-slim4 with json format ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh -j > /dev/null

echo "Testing openapi-slim4 with yaml format ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh -y > /dev/null

echo "Testing openapi-slim4 with yml format ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh -Y > /dev/null

echo "Tests completed (for results see: docker-test-environment/tests/codeception/_output)"