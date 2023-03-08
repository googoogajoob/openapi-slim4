<?php

namespace TestserverTest\codeception\error;

use ApiTester;
use Dotenv\Dotenv;

class ErrorHandlingCest
{
    protected string $exceptionSource;
    protected array $expectedMessage = [
        'Exception configuring Global Middleware',
        'Exception configuring Slim Routes',
        'Exception retrieving Path configuration data',
        'Slim Application is not defined',
        'Openapi object is not defined',
        'Exception resolving OpenApi Definition',
        'OpenApiDefinition must be of type',
        'Validation Error in the Openapi Definition',
        'Slim Version 4 is required'
    ];

    public function _before(ApiTester $I)
    {
    }

    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable('/var/www');
        $dotenv->safeLoad();
        $this->exceptionSource = getenv('throwExceptionOnInvalid') ? 'OpenApiSlim4.php' : 'index.php';
    }

    protected function responseContainsMessage(string $response, string $message): bool
    {
        $responseArray = json_decode($response, true);
        $exceptionMessage = $responseArray['exception'][0]['message'];
        $messageStrings = [$message, $this->exceptionSource];
        $returnValue = true;
        foreach ($messageStrings as $messageString) {
            if (!str_contains($exceptionMessage, $messageString)) {
                $returnValue = false;
                break;
            }
        }

        return $returnValue;
    }

    public function errorHandlingTest_00(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/foo');
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '500 Internal Server Error',
            'exception' => [
                'type' => 'Slim\Exception\HttpInternalServerErrorException',
                'code' => 500,
            ]
        ]);
        $response = $I->grabResponse();
        $I->assertTrue($this->responseContainsMessage($response, $this->expectedMessage[0]));
    }

    public function errorHandlingTest_01(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/foo');
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '500 Internal Server Error',
            'exception' => [
                'type' => 'Slim\Exception\HttpInternalServerErrorException',
                'code' => 500,
            ]
        ]);
        $response = $I->grabResponse();
        $I->assertTrue($this->responseContainsMessage($response, $this->expectedMessage[1]));
    }

    public function errorHandlingTest_02(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/foo');
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '500 Internal Server Error',
            'exception' => [
                'type' => 'Slim\Exception\HttpInternalServerErrorException',
                'code' => 500,
            ]
        ]);
        $response = $I->grabResponse();
        $I->assertTrue($this->responseContainsMessage($response, $this->expectedMessage[2]));
    }

    public function errorHandlingTest_03(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/foo');
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '500 Internal Server Error',
            'exception' => [
                'type' => 'Slim\Exception\HttpInternalServerErrorException',
                'code' => 500,
            ]
        ]);
        $response = $I->grabResponse();
        $I->assertTrue($this->responseContainsMessage($response, $this->expectedMessage[3]));
    }

    public function errorHandlingTest_04(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/foo');
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '500 Internal Server Error',
            'exception' => [
                'type' => 'Slim\Exception\HttpInternalServerErrorException',
                'code' => 500,
            ]
        ]);
        $response = $I->grabResponse();
        $I->assertTrue($this->responseContainsMessage($response, $this->expectedMessage[4]));
    }

    public function errorHandlingTest_05(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/foo');
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '500 Internal Server Error',
            'exception' => [
                'type' => 'Slim\Exception\HttpInternalServerErrorException',
                'code' => 500,
            ]
        ]);
        $response = $I->grabResponse();
        $I->assertTrue($this->responseContainsMessage($response, $this->expectedMessage[5]));
    }

    public function errorHandlingTest_06(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/foo');
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '500 Internal Server Error',
            'exception' => [
                'type' => 'Slim\Exception\HttpInternalServerErrorException',
                'code' => 500,
            ]
        ]);
        $response = $I->grabResponse();
        $I->assertTrue($this->responseContainsMessage($response, $this->expectedMessage[6]));
    }

    public function errorHandlingTest_07(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/foo');
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '500 Internal Server Error',
            'exception' => [
                'type' => 'Slim\Exception\HttpInternalServerErrorException',
                'code' => 500,
            ]
        ]);
        $response = $I->grabResponse();
        $I->assertTrue($this->responseContainsMessage($response, $this->expectedMessage[7]));
    }

    public function errorHandlingTest_08(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/foo');
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => '500 Internal Server Error',
            'exception' => [
                'type' => 'Slim\Exception\HttpInternalServerErrorException',
                'code' => 500,
            ]
        ]);
        $response = $I->grabResponse();
        $I->assertTrue($this->responseContainsMessage($response, $this->expectedMessage[8]));
    }
}