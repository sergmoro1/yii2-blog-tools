<?php

use sergmoro1\blog\tests\FunctionalTester;
use sergmoro1\blog\tests\common\_pages\LoginPage;
use sergmoro1\blog\tests\common\_views\CreateAuthorForm;
use sergmoro1\blog\models\Author;
use yii\Helpers\Url;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('login');

$loginPage = LoginPage::openBy($I);

$I->amGoingTo('login with correct credentials');
$loginPage->login('Sergey Morozov', '123456');
$I->expectTo('see that user is logged');
$I->seeLink('Logout');

$I->amGoingTo('see is there Author\'s list');
$I->click('Authors', '.nav');
$I->see('Authors', 'h3');

$I->wantTo('ensure Author CRUD works');

$I->amGoingTo('add Author');

$I->click('Add', '.btn-success');
$form = CreateAuthorForm::openBy($I);

$I->amGoingTo('submit form with no data');
$form->fill('');
$I->expectTo('see validations errors');
$I->see('Name cannot be blank.', '.help-block');

$I->amGoingTo('submit form with name');
$form->fill('Vasiya Pupkin');
$I->expectTo('see just added record');
$I->see('Vasiya Pupkin', '.table td');

$I->amGoingTo('update Author');
$id = Author::findOne(['name' => 'Vasiya Pupkin'])->id;

$I->amOnPage(Url::to(['author/update', 'id' => $id]));
$I->see('Update', 'h3');
$I->fillField('input[name="Author[name]"]', 'Вася Пупкин');
$I->click('Save', '.btn-success');
$I->expectTo('see just updated record');
$I->see('Вася Пупкин', '.table td');

$I->amGoingTo('delete Author');

$I->sendAjaxPostRequest(Url::to(['author/delete', 'id' => $id]));
$I->seeResponseCodeIs(302);
