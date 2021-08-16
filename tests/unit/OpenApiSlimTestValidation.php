<?php
declare(strict_types=1);

namespace OpenApiSlimTests\unit;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OpenApiSlimTests\unit\mocking\WrongSlimAppVersion;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use cebe\openapi\Reader;
use OpenApiSlim\OpenApiSlim;
use PHPUnit\Framework\TestCase;

class OpenApiSlimTestValidation extends TestCase
{
    public function testValidateBadOpenApiDefinition_0()
    {
        $testClass = $this->getTestClass(null, $this->getSlimApp());
        $this->assertFalse($testClass->validate());
    }

    public function testValidateBadOpenApiDefinition_1()
    {
        $testClass = $this->getTestClass([], $this->getSlimApp());
        $this->assertFalse($testClass->validate());
    }

    public function testValidateGoodOpenApiDefinition()
    {
        $testClass = $this->getTestClass([], $this->getSlimApp());
        $this->assertFalse($testClass->validate());
    }

    public function testValidateWrongSlimVersion()
    {
        $testClass = $this->getTestClass($this->getOpenapiDefinition(__DIR__ . '/../openapi/good-petstore.yaml'), $this->getWrongSlimAppVersion());
        $this->assertFalse($testClass->validate());
    }

    public function testValidateNoRoutesDefined()
    {
        $testClass = $this->getTestClass($this->getOpenapiDefinition(__DIR__ . '/../openapi/bad-petstore-no-routes-defined.yaml'), $this->getSlimApp());
        $this->assertFalse($testClass->validate());
    }

    public function testValidateHttpMethodNotAllowed()
    {
        $this->assertTrue(false);
    }

    public function testValidateHandlerClassNotFound()
    {
        $this->assertTrue(false);
    }

    public function testValidateHandlerClassMethodNotFound()
    {
        $this->assertTrue(false);
    }

    public function testValidate_Success()
    {
        $testClass = $this->getTestClass($this->getOpenapiDefinition(__DIR__ . '/../openapi/good-petstore.yaml'), $this->getSlimApp());
        $this->assertTrue($testClass->validate());
    }

/**************** Supporting Methods **********************************/

    protected function getTestClass($apiDefinition, $slimApp): OpenApiSlim
    {
        return new OpenApiSlim($apiDefinition, $slimApp, $this->getLogger());
    }

    protected function getOpenapiDefinition(string $filePath)
    {
        $cebeReader = new Reader();

        return $cebeReader::readFromYamlFile($filePath);
    }

    protected function getSlimApp(): App
    {
        $responseFactory = AppFactory::DetermineResponseFactory();
        $slimApp = new App($responseFactory);

        return $slimApp;
    }

    protected function getWrongSlimAppVersion(): App
    {
        $responseFactory = AppFactory::DetermineResponseFactory();
        $slimApp = new WrongSlimAppVersion($responseFactory);

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
