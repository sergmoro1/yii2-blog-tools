<?php

use sergmoro1\blog\tests\AcceptanceTester;
use sergmoro1\blog\tests\common\_pages\LoginPage;
use sergmoro1\blog\models\Comment;
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

$I->amGoingTo('see is there Comment\'s list');
$I->click('Comments', '.nav');
$I->see('Comments', 'h3');

$I->wantTo('ensure Comment CRUD works');

$I->amGoingTo('reply to the comment with no data');
$I->click('.table td .reply');
$I->see('Reply', 'h1');
$I->submitForm('#comment-form', ['Comment' => [
    'content' => '',
]], '#submit-btn');
$I->see('Reply', 'h1');
$I->expectTo('see validations errors');
$I->see('Content cannot be blank.', '.help-block');

$I->amGoingTo('reply to the comment');
$I->submitForm('#comment-form', ['Comment' => [
    'content' => 'Thanks for your comment!',
]], '#submit-btn');

$I->expectTo('see just added record');
$I->see('Comments', 'h3');
$I->see('Thanks for your comment!', '.table td');
$I->dontSeeElement('.table td .reply');
$I->seeElement('.table td .update');

$I->amGoingTo('update last comment');
$I->click(Locator::elementAt('.table tr td .update', -1));
$I->see('Update', 'h1');
$I->seeInField('Comment[content]', 'Thanks for your comment!');
$I->submitForm('#comment-form', ['Comment' => [
    'content' => 'Thanks a lot for your comment!',
]], '#submit-btn');

$I->expectTo('see just updated record');
$I->see('Comments', 'h3');
$I->see('Thanks a lot for your comment!', '.table td');
