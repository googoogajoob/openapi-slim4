<?php

$settings = [
    'displayErrorDetails' => true,
    'logError' => true,
    'logErrorDetails' => true,
    'throwExceptionOnInvalid' => getenv('throwExceptionOnInvalid') ? true : false,
    'nativeSlimConfiguration' => getenv('NATIVE_SLIM_CONFIG'),
    'openApiPath' => getenv('OPENAPI_PATH') ? getenv('OPENAPI_PATH') : __DIR__ . '/openapi.yml'
];