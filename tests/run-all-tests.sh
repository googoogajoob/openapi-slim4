#!/bin/bash

echo "NATIVE_SLIM_CONFIG=1" > ./docker-test-environment/.env
echo "OPENAPI_PATH=/var/www/config/openapi.yml" >> ./docker-test-environment/.env
docker exec --env NATIVE_SLIM_CONFIG=0 docker-test-environment_slim4-test_1 /usr/local/bin/php /var/www/vendor/bin/codecept --report run api

echo "NATIVE_SLIM_CONFIG=0" > ./docker-test-environment/.env
echo "OPENAPI_PATH=/var/www/config/openapi.yml" >> ./docker-test-environment/.env
docker exec --env NATIVE_SLIM_CONFIG=1 docker-test-environment_slim4-test_1 /usr/local/bin/php /var/www/vendor/bin/codecept --report run api

#echo "NATIVE_SLIM_CONFIG=1" > ./docker-test-environment/.env
#echo "OPENAPI_PATH=/var/www/config/openapi.yml" >> ./docker-test-environment/.env
#docker exec docker-test-environment_slim4-test_1 /usr/local/bin/php /var/www/vendor/bin/codecept run api PathFooCest::GetFooTest