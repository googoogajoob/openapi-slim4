#!/bin/bash

docker exec --env NATIVE_SLIM_CONFIG=0 docker-test-environment_slim4-test_1 /usr/local/bin/php /var/www/vendor/bin/codecept --report run api
docker exec --env NATIVE_SLIM_CONFIG=1 docker-test-environment_slim4-test_1 /usr/local/bin/php /var/www/vendor/bin/codecept --report run api