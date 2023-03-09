#!/bin/bash

### Set other variables
ENVFILE="/var/www/.env"
CODECEPTION="/usr/local/bin/php /var/www/vendor/bin/codecept"
MAKE_TEST_RESULTS_READABLE="chown 1000:1000 -R /var/www/tests/codeception/_output"

function print_help {
  echo "Usage: run_error_test.sh [OPTIONS]"
  echo "Options:"
  echo "  -e    Test validation error cases throwing OpenApiSlim4 exception"
  echo "  -E    Test validation error cases without throwing OpenApiSlim4 exception"
  echo "  -h    Display this help message"
  echo ""
}

while getopts ":eEh" opt; do
  case ${opt} in
    e )
      echo "Option -$opt specified, error cases throwing OpenApiSlim4 exception"
      echo "throwExceptionOnInvalid=1" > $ENVFILE
      DIRECTORY_SUFFIX="ErrorException"
      ;;
    E )
      echo "Option -$opt specified, error cases without throwing OpenApiSlim4 exception"
      echo "throwExceptionOnInvalid=0" > $ENVFILE
      DIRECTORY_SUFFIX="ErrorNoException"
      ;;
    h )
      print_help
      exit 0
      ;;
    \? )
      echo "Invalid option: -$OPTARG"
      exit 1
      ;;
    : )
      echo "Option -$OPTARG requires an argument."
      exit 1
      ;;
    * )
      echo "Invalid option: -$OPTARG" 1>&2
      exit 1
      ;;
  esac
done

#### Run the Test(s)

echo "OPENAPI_PATH=/var/www/config/openapi_$opt.yml" >> $ENVFILE
$CODECEPTION run --override "paths: output: tests/codeception/_output/OpenApiSlim4_$DIRECTORY_SUFFIX" -- errHandling ErrorHandlingCest

$MAKE_TEST_RESULTS_READABLE