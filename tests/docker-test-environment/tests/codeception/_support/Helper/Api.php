<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module;
use Codeception\TestInterface;

class Api extends Module
{
    public function _after(TestInterface $test)
    {
        $cestName = $test->getTestClass()::class;
        $testName = $test->getTestMethod();
        $filePath = codecept_output_dir() . $cestName . '_' . $testName . '.json';
        $fileContents = $this->moduleContainer->getModule('REST')->grabResponse();
        file_put_contents($filePath, $fileContents);
    }
}
