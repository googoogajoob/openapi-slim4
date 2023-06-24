<?php

namespace TestserverTest\codeception\api;

use ApiTester;

class MiddlewareBarCest
{
    public function _before(ApiTester $I)
    {
    }
    
    public function getBarTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('/bar');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function postBarTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware1', 'OutgoingMiddleware2', 'OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPost('/bar');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function putBarTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPut('/bar');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function patchBarTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPatch('/bar');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function deleteBarTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendDelete('/bar');
        $response = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function optionsBarTest(ApiTester $I)
    {
        $expectedMessage = [
            'incoming' => ['IncomingMiddleware2', 'IncomingMiddleware1'],
            'outgoing' => ['OutgoingMiddleware4', 'OutgoingMiddleware5']
        ];
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendOptions('/bar');
        $I->sendOptions('/foo');
        $response = json_decode($I->grabResponse(), true) ?? [];
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedMessage);
        $I->assertTrue($expectedMessage === $response['middleware'], 'Unexpected Middleware Message');
    }

    public function headBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendHead('/bar');
        $I->seeResponseCodeIs(501);
    }

    public function notFoundTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGET('/xbar');
        $I->seeResponseCodeIs(404);
    }
}
