#!/bin/bash

docker exec docker-test-environment_slim4-test_1 /usr/local/bin/php /var/www/vendor/bin/codecept --report run api