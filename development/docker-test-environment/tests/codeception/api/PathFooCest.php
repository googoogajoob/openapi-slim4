<?php

class PathFooCest
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
        $I->seeResponseContainsJson(['data' => 'GET __invoke handler']);
    }

    public function postFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPost('/foo');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['data' => 'POST __invoke handler']);
    }

    public function putFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPut('/foo');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['data' => 'PUT handler']);
    }

    public function patchFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendPatch('/foo');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['data' => 'PATCH handler']);
    }

    public function deleteFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendDelete('/foo');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['data' => 'DELETE __invoke handler']);
    }

    public function optionsFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendOptions('/foo');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['data' => 'OPTIONS __invoke handler']);
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
