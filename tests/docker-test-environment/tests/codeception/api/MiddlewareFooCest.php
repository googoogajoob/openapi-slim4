<?php

class MiddlewareFooCest
{
    public function _before(ApiTester $I)
    {
    }

    public function getFooTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function postFooTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware3', 'OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPost('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function putFooTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPut('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function patchFooTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPatch('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function deleteFooTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendDelete('/foo');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function optionsFooTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendOptions('/foo');
        $response = json_decode($response, true) ?? [];
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function headFooTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendHead('/foo');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function traceFooTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->send('TRACE', '/foo');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function notFoundTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGET('/xfoo');
        $I->seeResponseCodeIs(404);
    }
}
