<?php
/**
 * Project openapi-slim4
 * Created by PhpStorm.
 * User: and
 * Date: 09.07.21
 * Time: 11:53
 */

namespace OpenApiSlimTests\unit;

use cebe\openapi\Reader;
use cebe\openapi\SpecObjectInterface;
use OpenApiSlim\OpenApiSlim;
use PHPUnit\Framework\TestCase;

class OpenApiSlimTest extends TestCase
{
    public function testPetStoreValidation()
    {
        $testClass = $this->getTestClass($this->getPetStore());
        $this->assertTrue($testClass->validate());
    }

    public function testPetStoreConfiguration()
    {
        $testClass = $this->getTestClass($this->getPetStore());
        $this->assertTrue($testClass->validate());
        $this->assertTrue($testClass->configureSlim());
    }

    public function testBadPetStoreValidation()
    {
        $testClass = $this->getTestClass($this->getBadPetStore());
        $this->assertFalse($testClass->validate());
    }

    protected function getTestClass(SpecObjectInterface $apiDefinition): OpenApiSlim
    {
        return new OpenApiSlim($apiDefinition->paths->getPaths(), null);
    }

    protected function getPetStore(): SpecObjectInterface
    {
        $cebeReader = new Reader();

        return $cebeReader::readFromYamlFile(__DIR__ . '/../openapi/official-examples/petstore.yaml');
    }

    protected function getBadPetStore(): SpecObjectInterface
    {
        $cebeReader = new Reader();

        return $cebeReader::readFromYamlFile(__DIR__ . '/../openapi/bad-petstore.yaml');
    }
}
