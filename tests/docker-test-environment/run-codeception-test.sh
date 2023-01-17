#!/bin/bash

if [ $# -ne 3 ]
  then
    echo "Three arguments required:"
    echo "   SLIM for native slim (otherwise openapi-slim4)"
    echo "   JSON openapi format (otherwise yaml)"
    echo "   CLEAN clear output results (otherwise no cleaning)"
    exit
fi

### Initialize Variables

ENVFILE="/var/www/.env"
CODECEPTION="/usr/local/bin/php /var/www/vendor/bin/codecept"
MAKE_TEST_RESULTS_READABLE="chown 1000:1000 -R /var/www/tests/codeception/_output"

if [ $1 == "SLIM" ];
then
  SLIMCONFIG="NATIVE_SLIM_CONFIG=1"
  CONFIG_OPTION="slim"
else
  SLIMCONFIG="NATIVE_SLIM_CONFIG=0"
  CONFIG_OPTION="openapiSlim4"
fi

if [ $2 == "JSON" ];
then
  OPENAPI_FORMAT="OPENAPI_PATH=/var/www/config/openapi.json"
  FORMAT_OPTION="json"
else
  OPENAPI_FORMAT="OPENAPI_PATH=/var/www/config/openapi.yml"
  FORMAT_OPTION="yaml"
fi

#### Run Tests

if [ $3 == "CLEAN" ];
then
  $CODECEPTION clean
fi

echo $SLIMCONFIG > $ENVFILE
echo $OPENAPI_FORMAT >> $ENVFILE
$CODECEPTION run --override "paths: output: tests/codeception/_output/$CONFIG_OPTION"_"$FORMAT_OPTION" -- api

$MAKE_TEST_RESULTS_READABLE