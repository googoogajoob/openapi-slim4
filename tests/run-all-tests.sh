#!/bin/bash
echo "Testing native slim configuration ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh SLIM x CLEAN > /dev/null
echo "Testing openapi-slim4 with json format ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh x JSON x > /dev/null
echo "Testing openapi-slim4 with yaml format ..."
docker exec -it docker-test-environment_slim4-test_1 /var/www/run-codeception-test.sh x x x > /dev/null
echo "Tests completed"