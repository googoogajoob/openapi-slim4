<?php
declare(strict_types=1);

namespace OpenApiSlimTests\unit;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OpenApiSlimTests\unit\mocking\BadSlimApp;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use cebe\openapi\Reader;
use cebe\openapi\SpecObjectInterface;
use OpenApiSlim\OpenApiSlim;
use PHPUnit\Framework\TestCase;

class OpenApiSlimTest extends TestCase
{
    public function testGoodValidation()
    {
        $testClass = $this->getTestClass($this->getPetStore(), $this->getSlimApp());
        $this->assertTrue($testClass->validate());
    }

    public function testBadValidationOpenApi()
    {
        $testClass = $this->getTestClass(null, $this->getSlimApp());
        $this->assertFalse($testClass->validate());
    }

    public function testBadValidationSlim()
    {
        $testClass = $this->getTestClass($this->getPetStore(), $this->getBadSlimApp());
        $this->assertFalse($testClass->validate());
    }

    public function testPetStoreConfiguration()
    {
        $testClass = $this->getTestClass($this->getPetStore(), $this->getSlimApp());
        $this->assertTrue($testClass->configureSlim());
    }

    public function testBadPetStoreValidation()
    {
        $testClass = $this->getTestClass($this->getBadPetStore(), $this->getSlimApp());
        $this->assertFalse($testClass->configureSlim());
    }

    protected function getTestClass($apiDefinition, $slimApp): OpenApiSlim
    {
        return new OpenApiSlim($apiDefinition, $slimApp, $this->getLogger());
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

    protected function getSlimApp(): App
    {
        $responseFactory = AppFactory::DetermineResponseFactory();
        $slimApp = new App($responseFactory);

        return $slimApp;
    }

    protected function getBadSlimApp(): App
    {
        $responseFactory = AppFactory::DetermineResponseFactory();
        $slimApp = new BadSlimApp($responseFactory);

        return $slimApp;
    }

    protected function getLogger(): LoggerInterface
    {
        // create a log channel
        $log = new Logger('OpenApiSlim-PhpUnit-Test-Logger');
        $log->pushHandler(new StreamHandler('php://stderr', Logger::DEBUG));

        return $log;
    }
}
