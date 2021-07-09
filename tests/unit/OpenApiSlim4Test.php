<?php
/**
 * Project openapi-slim4
 * Created by PhpStorm.
 * User: and
 * Date: 09.07.21
 * Time: 11:53
 */

namespace openapislimtests\unit;

use openapislim\OpenApiSlim4;
use PHPUnit\Framework\TestCase;

class OpenApiSlim4Test extends TestCase
{

    public function testJunk()
    {
        $testClass = $this->getTestClass();
        $this->assertEquals('junk', $testClass->junk());
    }

    protected function getTestClass(): OpenApiSlim4
    {
        return new OpenApiSlim4();
    }
}
