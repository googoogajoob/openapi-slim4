<?php

class MiddlewareBarCest
{
    public function _before(ApiTester $I)
    {
    }
    
    public function getBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('/bar');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1']]);
    }

    public function postBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPost('/bar');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->assertArrayNotHasKey('message', $aResponse);
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1', 'PathMiddleware2', 'PathMiddleware1']]);
    }

    public function putBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPut('/bar');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1']]);
    }

    public function patchBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPatch('/bar');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1']]);
    }

    public function deleteBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendDelete('/bar');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1']]);
    }

    public function optionsBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendOptions('/bar');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1']]);
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
