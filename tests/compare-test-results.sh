#!/bin/bash
RESULTS_SLIM_DIRECTORY="docker-test-environment/tests/codeception/_output/slim_yaml"
RESULTS_OPENAPI_YAML_DIRECTORY="docker-test-environment/tests/codeception/_output/openapiSlim4_yaml"
RESULTS_OPENAPI_JSON_DIRECTORY="docker-test-environment/tests/codeception/_output/openapiSlim4_json"

#ls $RESULTS_SLIM_DIRECTORY
#echo
#ls $RESULTS_OPENAPI_YAML_DIRECTORY
#echo
#ls $RESULTS_OPENAPI_JSON_DIRECTORY

echo "Failed Tests"
echo "============"
if [ -f "$RESULTS_SLIM_DIRECTORY/failed" ]; then
  echo "Native Slim: " "$(wc -l $RESULTS_SLIM_DIRECTORY/failed | awk '{print $1}')"
fi
if [ -f "$RESULTS_OPENAPI_YAML_DIRECTORY/failed" ]; then
  echo "Openapi Yaml: " "$(wc -l $RESULTS_OPENAPI_YAML_DIRECTORY/failed | awk '{print $1}')"
fi
if [ -f "$RESULTS_OPENAPI_JSON_DIRECTORY/failed" ]; then
  echo "Openapi Json: " "$(wc -l $RESULTS_OPENAPI_JSON_DIRECTORY/failed | awk '{print $1}')"
fi

find $RESULTS_SLIM_DIRECTORY -name "*.json" -execdir bash -c "sed -i 's/Native Slim Configuration/Configuration/' {}" \;
find $RESULTS_SLIM_DIRECTORY -name "*.json" -execdir bash -c "sed -i 's/openapi.yml/openapi/' {}" \;
find $RESULTS_OPENAPI_YAML_DIRECTORY -name "*.json" -execdir bash -c "sed -i 's/OpenApiSlim4 Configuration/Configuration/' {}" \;
find $RESULTS_OPENAPI_YAML_DIRECTORY -name "*.json" -execdir bash -c "sed -i 's/openapi.yml/openapi/' {}" \;
find $RESULTS_OPENAPI_JSON_DIRECTORY -name "*.json" -execdir bash -c "sed -i 's/OpenApiSlim4 Configuration/Configuration/' {}" \;
find $RESULTS_OPENAPI_JSON_DIRECTORY -name "*.json" -execdir bash -c "sed -i 's/openapi.json/openapi/' {}" \;

echo
echo
echo "Compare Slim with openapi.yml"
echo "============================="
find $RESULTS_SLIM_DIRECTORY -name "*.json" -execdir bash -c "echo; echo {}; diff --color $(pwd)/$RESULTS_SLIM_DIRECTORY/{} $(pwd)/$RESULTS_OPENAPI_YAML_DIRECTORY/{}" \;

echo
echo
echo "Compare Slim with openapi.json"
echo "=============================="
find $RESULTS_SLIM_DIRECTORY -name "*.json" -execdir bash -c "echo; echo {}; diff --color $(pwd)/$RESULTS_SLIM_DIRECTORY/{} $(pwd)/$RESULTS_OPENAPI_JSON_DIRECTORY/{}" \;