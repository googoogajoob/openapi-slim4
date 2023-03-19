#!/bin/bash

cd /var/www || exit

#set parameter defaults
CLEAR=0
OPENAPI_SUFFIX=''

### Set other variables
ENVFILE="/var/www/.env"
CODECEPTION="/usr/local/bin/php /var/www/vendor/bin/codecept"
POST_PROCESSING="/var/www/tests/make-tests-readable.sh"

function print_help {
  echo "Usage: run_error_test.sh [OPTIONS]"
  echo "Options:"
  echo "  -e    Test validation error cases throwing OpenApiSlim4 exception"
  echo "  -E    Test validation error cases without throwing OpenApiSlim4 exception"
  echo "  -c    Clear previous test results prior to execution"
  echo "  -h    Display this help message"
  echo ""
}

while getopts ":eEch" opt; do
  case ${opt} in
    e )
      echo "Option -$opt specified, error cases throwing OpenApiSlim4 exception"
      echo "throwExceptionOnInvalid=1" > $ENVFILE
      DIRECTORY_SUFFIX="ErrorException"
      OPENAPI_SUFFIX=$opt
      ;;
    E )
      echo "Option -$opt specified, error cases without throwing OpenApiSlim4 exception"
      echo "throwExceptionOnInvalid=0" > $ENVFILE
      DIRECTORY_SUFFIX="ErrorNoException"
      OPENAPI_SUFFIX=$opt
      ;;
    c )
      CLEAR=1
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

if [ $CLEAR -ne 0 ]; then
  echo "Clearing previous test results"
  $CODECEPTION CLEAN
fi

#### Run the Test(s)

echo "OPENAPI_PATH=/var/www/config/openapi_$OPENAPI_SUFFIX?.yml" >> $ENVFILE
PHP_VERSION=$(php -r "echo PHP_VERSION;")
$CODECEPTION run --override 'paths: output: /var/www/tests/codeception/_output/'PHP_$PHP_VERSION/OpenApiSlim4_"$DIRECTORY_SUFFIX" -- errHandling ErrorHandlingCest
cat $ENVFILE
$POST_PROCESSING