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

class OpenApiSlimTest extends TestCase
{
    public function testValidate_BadOpenapiDefinition()
    {
        $testClass = $this->getTestClass([], $this->getSlimApp());
        $this->assertFalse($testClass->validate());
    }

    public function testValidate_WrongSlimVersion()
    {
        $testClass = $this->getTestClass($this->getOpenapiDefinition(__DIR__ . '/../openapi/good-petstore.yaml'), $this->getWrongSlimAppVersion());
        $this->assertFalse($testClass->validate());
    }

    public function testValidate_NoRoutesDefined()
    {
        $testClass = $this->getTestClass($this->getOpenapiDefinition(__DIR__ . '/../openapi/bad-petstore-no-routes-defined.yaml'), $this->getSlimApp());
        $this->assertFalse($testClass->validate());
    }

    public function testValidate_HttpMethodNotAllowed()
    {
        $this->assertTrue(false);
    }

    public function testValidate_HandlerClassNotFound()
    {
        $this->assertTrue(false);
    }

    public function testValidate_HandlerClassMethodNotFound()
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
