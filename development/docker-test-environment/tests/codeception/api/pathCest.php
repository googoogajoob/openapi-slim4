<?php

class pathCest
{
    public function _before(ApiTester $I)
    {
    }

    public function getFooTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $response = $I->sendGet('localhost/foo');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
