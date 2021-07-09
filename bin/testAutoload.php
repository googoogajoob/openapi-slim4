<?php

require "../vendor/autoload.php";

echo "Testing the Autoloader and Namespaces\n";

$testClass = new openapislim\OpenApiSlim4();
echo $testClass->junk() . "\n";