#!/bin/bash

#set parameter defaults
NATIVE_SLIM4=0
OPENAPI_FILE_EXTENSION="yaml"
CLEAR=0

### Set other variables
ENVFILE="/var/www/.env"
CODECEPTION="/usr/local/bin/php /var/www/vendor/bin/codecept"
MAKE_TEST_RESULTS_READABLE="chown 1000:1000 -R /var/www/tests/codeception/_output"

function print_help {
  echo "Usage: run_success_test.sh [OPTIONS]"
  echo "Options:"
  echo "  -s    Use native Slim4 configuration for routes/paths"
  echo "  -j    Use JSON format of the OpenApi Definition"
  echo "  -y    Use YML format of the OpenApi Definition"
  echo "  -Y    Use YAML format of the OpenApi Definition (same file, different extension)"
  echo "  -c    Clear previous test results prior to execution"
  echo "  -h    Display this help message"
  echo ""
  echo "The options s,j,y and Y are mutually exclusive. The default is -y"
}

function show_parameters {
  if [ $NATIVE_SLIM4 -ne 0 ]; then
    echo "Using Native Slim4 Definition as source"
  else
    echo "Using Openapi Definition as source"
    echo "File Extension: $OPENAPI_FILE_EXTENSION"
  fi
}

while getopts ":sjyYch" options; do
  case ${options} in
    s )
      NATIVE_SLIM4=1
      ;;
    j )
      OPENAPI_FILE_EXTENSION="json"
      ;;
    y )
      OPENAPI_FILE_EXTENSION="yml"
      ;;
    Y )
      OPENAPI_FILE_EXTENSION="yaml"
      ;;
    c )
      CLEAR=1
      ;;
    h )
      print_help
      exit 0
      ;;
    \? )
      echo "Invalid option: -$OPTARG" 1>&2
      exit 1
      ;;
    : )
      echo "Option -$OPTARG requires an argument." 1>&2
      exit 1
      ;;
    * )
      echo "Invalid option: -$OPTARG" 1>&2
      exit 1
      ;;
  esac
done

show_parameters

if [ $CLEAR -ne 0 ]; then
  echo "Clearing previous test results"
  $CODECEPTION CLEAN
fi

#### Run the Tests

echo "NATIVE_SLIM_CONFIG=$NATIVE_SLIM4" > $ENVFILE
if [ $NATIVE_SLIM4 -eq 0 ]; then
  echo "OPENAPI_PATH=/var/www/config/openapi.$OPENAPI_FILE_EXTENSION" >> $ENVFILE
  $CODECEPTION run --override "paths: output: tests/codeception/_output/OpenApiSlim4"_"$OPENAPI_FILE_EXTENSION" -- api
else
  $CODECEPTION run --override "paths: output: tests/codeception/_output/Slim4" -- api
fi

$MAKE_TEST_RESULTS_READABLE