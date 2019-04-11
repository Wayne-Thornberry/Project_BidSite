<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class FirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
    }


    public function loginTest(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('username', 'davert');
        $I->fillField('password', 'qwerty');
        $I->click('Login');
    }
}
