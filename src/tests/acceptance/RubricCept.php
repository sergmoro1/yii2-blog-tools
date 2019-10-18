<?php

use sergmoro1\blog\tests\AcceptanceTester;
use sergmoro1\blog\tests\common\_pages\LoginPage;
use sergmoro1\blog\models\Rubric;

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

$I->amGoingTo('see is there Rubric\'s list');
$I->click('Rubrics', '.nav');
$I->see('Rubrics', 'h3');

$I->wantTo('ensure Rubric CRUD works');

$I->amGoingTo('submit form with no data');
$I->click('Add', '.btn-success');
$I->see('Add', 'h1');
$I->submitForm('#rubric-form', ['Rubric' => [
    'parent_node' => '1',
    'position' => '',
    'show' => '1',
    'name' => '',
    'slug' => '',
]], '#submit-btn');
$I->see('Add', 'h1');
$I->expectTo('see validations errors');
$I->see('Name cannot be blank.', '.help-block');
$I->see('Slug cannot be blank.', '.help-block');

$I->amGoingTo('add rubric');
$I->submitForm('#rubric-form', ['Rubric' => [
    'parent_node' => '1',
    'position' => '100',
    'show' => '1',
    'name' => 'News',
    'slug' => 'news',
]], '#submit-btn');
$I->expectTo('see just added record');
$I->see('Rubrics', 'h3');
$I->see('news', '.table td small');

$I->amGoingTo('add some next rubric with the same values');
$I->click('Add', '.btn-success');
$I->see('Add', 'h1');
$I->submitForm('#rubric-form', ['Rubric' => [
    'parent_node' => '1',
    'position' => '100',
    'show' => '1',
    'name' => 'Events',
    'slug' => 'news',
]], '#submit-btn');
$I->see('Add', 'h1');
$I->expectTo('see validations errors');
$I->see('Position "100" has already been taken.', '.help-block');
$I->see('Slug "news" has already been taken.', '.help-block');

$I->amGoingTo('delete the just added rubric');
$model = Rubric::findOne(['slug' => 'news']);
$model->deleteWithChildren();
$I->dontSeeInDatabase('rubric', ['slug' => 'news']);
$I->seeInDatabase('rubric', ['slug' => 'root', 'rgt' => 2]);
