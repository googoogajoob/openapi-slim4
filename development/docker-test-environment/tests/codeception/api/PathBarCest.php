<?php

class PathBarCest
{
    const JSON_RESPONSE = ['data' => 'Test Response'];

    public function _before(ApiTester $I)
    {
    }

    public function getBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('localhost/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(json_encode(self::JSON_RESPONSE));
    }

    public function postBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('localhost/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(json_encode(self::JSON_RESPONSE));
    }

    public function putBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('localhost/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(json_encode(self::JSON_RESPONSE));
    }

    public function patchBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('localhost/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(json_encode(self::JSON_RESPONSE));
    }

    public function deleteBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('localhost/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(json_encode(self::JSON_RESPONSE));
    }

    public function optionsBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('localhost/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(json_encode(self::JSON_RESPONSE));
    }

    public function headBarTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('localhost/bar');
        $I->seeResponseCodeIs(501);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(json_encode(self::JSON_RESPONSE));
    }
}
