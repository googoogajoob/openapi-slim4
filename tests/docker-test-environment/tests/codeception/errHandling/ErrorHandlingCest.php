<?php

namespace TestserverTest\codeception\errHandling;

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

    /**
     * @param string $response
     * @param string $message
     * @return bool
     */
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

    /**
     * @param int $testNumber
     * @return void
     */
    protected function setEnvfile(int $testNumber)
    {
        $envfile=file_get_contents('/var/www/.env');
        $envfile=substr(trim($envfile), 0, -5). $testNumber . '.yml';
        file_put_contents('/var/www/.env', $envfile);
    }

    /**
     * @param ApiTester $I
     * @return void
     */
    public function errorHandlingTest_00(ApiTester $I)
    {
        $this->setEnvfile(0);
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

    /**
     * @param ApiTester $I
     * @return void
     *
     * @skip ToDo: I'm unsure how to create this error by falsely configuring the openapi definition
     */
    public function errorHandlingTest_01(ApiTester $I)
    {
        $this->setEnvfile(1);
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

    /**
     * @param ApiTester $I
     * @return void
     */
    public function errorHandlingTest_02(ApiTester $I)
    {
        $this->setEnvfile(2);
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

    /**
     * @param ApiTester $I
     * @return void
     *
     * @skip ToDo: I'm unsure how to create this error by falsely configuring the openapi definition
     */
    public function errorHandlingTest_03(ApiTester $I)
    {
        $this->setEnvfile(3);
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

    /**
     * @param ApiTester $I
     * @return void
     *
     * @skip ToDo: I'm unsure how to create this error by falsely configuring the openapi definition
     */
    public function errorHandlingTest_04(ApiTester $I)
    {
        $this->setEnvfile(4);
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

    /**
     * @param ApiTester $I
     * @return void
     */
    public function errorHandlingTest_05(ApiTester $I)
    {
        $this->setEnvfile(9);
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

    /**
     * @param ApiTester $I
     * @return void
     *
     * @skip
     */
    public function errorHandlingTest_06(ApiTester $I)
    {
        $this->setEnvfile(6);
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

    /**
     * @param ApiTester $I
     * @return void
     */
    public function errorHandlingTest_07(ApiTester $I)
    {
        $this->setEnvfile(7);
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

    /**
     * @param ApiTester $I
     * @return void
     *
     * @skip
     */
    public function errorHandlingTest_08(ApiTester $I)
    {
        $this->setEnvfile(8);
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