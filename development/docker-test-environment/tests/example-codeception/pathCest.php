<?php

use Codeception\Module\REST;

class PathCest extends REST
{

    public function createUserViaAPI($I)
    {
        $I->amHttpAuthenticated('service_user', '123456');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPost('/users', [
            'name' => 'davert',
            'email' => 'davert@codeception.com'
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"result":"ok"}');

    }
}