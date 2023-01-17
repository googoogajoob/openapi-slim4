#!/bin/bash

TEST_ENVFILE="./docker-test-environment/.env"

echo "Testing native slim configuration ..."
echo "CLEAN" > $TEST_ENVFILE
echo "SLIM" >> $TEST_ENVFILE
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh > /dev/null

echo "Testing openapi-slim4 with json format ..."
echo "OPENAPI=openapi.json" >> $TEST_ENVFILE
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh > /dev/null

echo "Testing openapi-slim4 with yaml format ..."
echo "OPENAPI=openapi.yaml" >> $TEST_ENVFILE
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh x x x > /dev/null

echo "Testing openapi-slim4 with yml format ..."
echo "OPENAPI=openapi.yml" >> $TEST_ENVFILE
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh x x x > /dev/null

echo "Tests completed (for results see: docker-test-environment/tests/codeception/_output)"