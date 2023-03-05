<?php


class ErrorHandlingCest
{
    public function _before(ApiTester $I)
    {
    }

    public function getFooTest(ApiTester $I)
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
                'message' => 'ERROR: Uncaught Exception: DUDE, where is my error?'
            ]
        ]);
    }
}
