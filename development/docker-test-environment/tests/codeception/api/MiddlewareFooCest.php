<?php

class MiddlewareFooCest
{
    public function _before(ApiTester $I)
    {
    }

    public function getFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('/foo');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1', 'PathMiddleware2', 'PathMiddleware1']]);
    }

    public function postFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPost('/foo');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1', 'PathMiddleware2', 'PathMiddleware1']]);
    }

    public function putFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPut('/foo');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1', 'PathMiddleware2', 'PathMiddleware1']]);
    }

    public function patchFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPatch('/foo');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1', 'PathMiddleware2', 'PathMiddleware1']]);
    }

    public function deleteFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendDelete('/foo');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1', 'PathMiddleware2', 'PathMiddleware1']]);
    }

    public function optionsFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendOptions('/foo');
        $aResponse = json_decode($response, true);
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => ['GlobalMiddleware3', 'GlobalMiddleware2', 'GlobalMiddleware1', 'PathMiddleware2', 'PathMiddleware1']]);
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
