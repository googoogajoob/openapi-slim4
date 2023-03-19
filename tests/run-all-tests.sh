#!/bin/bash

function run_tests
{
  CONTAINER_PHP_VERSION=$(docker exec -it $CONTAINER php -r 'echo PHP_VERSION . "\n";')
  echo Testing with PHP $CONTAINER_PHP_VERSION

  echo "- native slim configuration ..."
  docker exec -it $CONTAINER /var/www/tests/run-success-test.sh -s > /dev/null

  echo "- openapi-slim4 with json format ..."
  docker exec -it $CONTAINER /var/www/tests/run-success-test.sh -j > /dev/null

  echo "- openapi-slim4 with yaml format ..."
  docker exec -it $CONTAINER /var/www/tests/run-success-test.sh -y > /dev/null

  echo "- openapi-slim4 with yml format ..."
  docker exec -it $CONTAINER /var/www/tests/run-success-test.sh -Y > /dev/null

  echo "- openapi-slim4 error handling with validation exception ..."
  docker exec -it $CONTAINER /var/www/tests/run-error-test.sh -e > /dev/null

  echo "- openapi-slim4 error handling with NO validation exception ..."
  docker exec -it $CONTAINER /var/www/tests/run-error-test.sh -E > /dev/null

  echo
}

function error_report
{
  echo "Tests completed (for results see: docker-test-environment/tests/codeception/_output)"
  echo
  echo "Failed tests contain the word 'fail' in the file name. Here is a list ..."
  find docker-test-environment/tests/codeception/_output -name "*fail*"
  echo
}

# Remove existing test Results
rm -fr /var/www/tests/codeception/_output/*

for PHP_VERSION in "80" "81" "82"
do
  CONTAINER=docker-test-environment_slim4-test-php"$PHP_VERSION"_1
  run_tests
done

error_report