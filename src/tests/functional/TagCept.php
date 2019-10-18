<?php

use sergmoro1\blog\tests\FunctionalTester;
use sergmoro1\blog\tests\common\_pages\LoginPage;
use sergmoro1\blog\models\Tag;
use yii\Helpers\Url;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('login');

$loginPage = LoginPage::openBy($I);

$I->amGoingTo('login with correct credentials');
$loginPage->login('Sergey Morozov', '123456');
$I->expectTo('see that user is logged');
$I->seeLink('Logout');

$I->amGoingTo('see is there Tag\'s list');
$I->click('Tags', '.nav');
$I->see('Tags', 'h3');
$I->see('yii-two', '.table tr td');

$I->amGoingTo('delete yii-two tag');
$model = Tag::findOne(['name' => 'yii-two']);
$I->sendAjaxPostRequest(Url::to(['/blog/tag/delete', 'id' => $model->id]));
$I->seeResponseCodeIs(302);

$I->click('Posts', '.nav');
$I->see('Posts', 'h3');
$I->dontSee('yii-two', '.table tr td');
