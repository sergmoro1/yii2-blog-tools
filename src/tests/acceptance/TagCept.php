<?php

use sergmoro1\blog\tests\AcceptanceTester;
use sergmoro1\blog\tests\common\_pages\LoginPage;
use sergmoro1\blog\models\Tag;
use \Codeception\Util\Locator;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('login');

$loginPage = LoginPage::openBy($I);

$I->amGoingTo('login with correct credentials');
$loginPage->login('Sergey Morozov', '123456');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}

$I->seeLink('Sergey Morozov');
$I->click('Sergey Morozov');
$I->seeLink('Logout');

$I->amGoingTo('see is there Tag\'s list');
$I->click('Tags', '.nav');
$I->see('Tags', 'h3');

$I->wantTo('ensure Tag CRUD works');

$I->amGoingTo('update last tag');
$I->click(Locator::elementAt('.table tr td .update', -1));
$I->see('Update', 'h1');
$I->seeInField('Tag[name]', 'yii-two');
$I->submitForm('#tag-form', ['Tag' => [
    'name' => 'Yii2',
    'show' => 0,
]], '#submit-btn');

$I->expectTo('see just updated record');
$I->see('Tags', 'h3');
$I->see('Yii2', '.table tr td');

$I->amGoingTo('see is there new value for has just updated tag in a post');
$I->click('Posts', '.nav');
$I->see('Posts', 'h3');
$I->see('Yii2', '.table tr td');
