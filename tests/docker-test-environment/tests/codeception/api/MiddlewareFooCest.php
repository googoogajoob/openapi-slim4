<?php

class MiddlewareFooCest
{
    public function _before(ApiTester $I)
    {
    }

    public function getFooTest(ApiTester $I)
    {
        $expectedMessage = ['GlobalMiddleware1', 'GlobalMiddleware2', 'GlobalMiddleware3'];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['message'], 'Unexpected Middleware Message');
    }

    public function postFooTest(ApiTester $I)
    {
        $expectedMessage = ['PostMiddleware2', 'GlobalMiddleware1', 'GlobalMiddleware2', 'GlobalMiddleware3'];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPost('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['message'], 'Unexpected Middleware Message');
    }

    public function putFooTest(ApiTester $I)
    {
        $expectedMessage = ['GlobalMiddleware1', 'GlobalMiddleware2', 'GlobalMiddleware3'];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPut('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['message'], 'Unexpected Middleware Message');
    }

    public function patchFooTest(ApiTester $I)
    {
        $expectedMessage = ['GlobalMiddleware1', 'GlobalMiddleware2', 'GlobalMiddleware3'];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPatch('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['message'], 'Unexpected Middleware Message');
    }

    public function deleteFooTest(ApiTester $I)
    {
        $expectedMessage = ['GlobalMiddleware1', 'GlobalMiddleware2', 'GlobalMiddleware3'];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendDelete('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['message'], 'Unexpected Middleware Message');
    }

    public function optionsFooTest(ApiTester $I)
    {
        $expectedMessage = ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1'];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendOptions('/foo');
        $response = json_decode($response, true) ?? [];
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['message'], 'Unexpected Middleware Message');
    }

    public function headFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendHead('/foo');
        $I->seeResponseCodeIs(501);
    }

    public function notFoundTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGET('/xfoo');
        $I->seeResponseCodeIs(404);
    }
}
