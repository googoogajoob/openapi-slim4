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
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        assertArrayNotHasKey('message', $response);
    }

    public function postBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPost('/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        assertArrayNotHasKey('message', $response);
    }

    public function putBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPut('/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        assertArrayNotHasKey('message', $response);
    }

    public function patchBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPatch('/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        assertArrayNotHasKey('message', $response);
    }

    public function deleteBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendDelete('/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        assertArrayNotHasKey('message', $response);
    }

    public function optionsBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendOptions('/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        assertArrayNotHasKey('message', $response);
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
