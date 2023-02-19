#!/bin/bash

#set parameter defaults
DEFINITION_SOURCE="OPENAPI"
OPENAPI_FORMAT="YAML"
CLEAN=0

### Set other variables
ENVFILE="/var/www/.env"
CODECEPTION="/usr/local/bin/php /var/www/vendor/bin/codecept"
MAKE_TEST_RESULTS_READABLE="chown 1000:1000 -R /var/www/tests/codeception/_output"

function print_help {
  echo "Usage: run_codeception_test.sh [OPTIONS]"
  echo "Options:"
  echo "  -h    Display this help message"
  echo "  -s    Bypass openapi-slim4 (this extension) and use the native Slim4 Definition for routes/paths"
  echo "  -j    Use JSON format of the OpenApi Definition (default is yaml)"
  echo "  -c    Clean test results prior to test execution"
}

function show_parameters {
  if [ $DEFINITION_SOURCE == "OPENAPI" ]; then
    echo "Using Openapi Definition as source"
    if [ $OPENAPI_FORMAT == 'YAML' ]; then
      echo "Using YAML Openapi format"
    else
      echo "Using JSON Openapi format"
    fi
  else
    echo "Using Native Slim4 Definition as source"
  fi
  if [ $CLEAN -ne 0 ]; then
    echo "Clear previous test results"
  fi
}

while getopts ":sjch" options; do
  case ${options} in
    s )
      DEFINITION_SOURCE="SLIM4"
      ;;
    j )
      OPENAPI_FORMAT="JSON"
      ;;
    c )
      CLEAN=1
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

if [ $DEFINITION_SOURCE == "OPENAPI" ]; then
  SLIMCONFIG="NATIVE_SLIM_CONFIG=0"
  CONFIG_OPTION="openapiSlim4"
else
  SLIMCONFIG="NATIVE_SLIM_CONFIG=1"
  CONFIG_OPTION="slim"
fi
if [ $OPENAPI_FORMAT == 'YAML' ]; then
  OPENAPI_FORMAT="OPENAPI_PATH=/var/www/config/openapi.yml"
  FORMAT_OPTION="yaml"
else
  OPENAPI_FORMAT="OPENAPI_PATH=/var/www/config/openapi.json"
  FORMAT_OPTION="json"
fi
if [ $CLEAN -ne 0 ]; then
  echo "Dude, I'm cleaning up..."
  $CODECEPTION clean
else
  echo "Ich lass den Dreck bleiben, tschüß..."
fi

#exit 1 "Not finished yet, needs some ToDos"
exit 1

#### Run the Tests

echo $SLIMCONFIG > $ENVFILE
echo $OPENAPI_FORMAT >> $ENVFILE
$CODECEPTION run --override "paths: output: tests/codeception/_output/$CONFIG_OPTION"_"$FORMAT_OPTION" -- api

$MAKE_TEST_RESULTS_READABLE