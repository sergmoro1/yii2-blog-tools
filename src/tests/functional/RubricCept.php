<?php

use sergmoro1\blog\tests\FunctionalTester;
use sergmoro1\blog\tests\common\_pages\LoginPage;
use sergmoro1\blog\models\Rubric;
use yii\Helpers\Url;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('login');

$loginPage = LoginPage::openBy($I);

$I->amGoingTo('login with correct credentials');
$loginPage->login('Sergey Morozov', '123456');
$I->expectTo('see that user is logged');
$I->seeLink('Logout');

$I->seeInDatabase('rubric', ['slug' => 'root', 'rgt' => 2]);

$I->amGoingTo('to add rubric programmatically');

$root = Rubric::findOne(['slug' => 'root']);
$model = new Rubric(['parent_node' => $root->id, 'position' => 100, 'name' => 'News', 'slug' => 'news']);
$model->prependTo($root);

$I->expectTo('see new values in DB');
$I->expectTo('see the record with slug = "root" has rgt=4');
$I->assertEquals(Rubric::findOne(['slug' => 'root'])->rgt, 4);
$I->expectTo('see the record with slug = "news" has level=2');
$I->assertEquals(Rubric::findOne(['slug' => 'news'])->level, 2);

$I->amGoingTo('delete just added rubric');
$I->sendAjaxPostRequest(Url::to(['rubric/delete', 'id' => $model->id]));
$I->seeResponseCodeIs(302);

$I->expectTo('see the record with slug = "root" has rgt=2 again');
$I->assertEquals(Rubric::findOne(['slug' => 'root'])->rgt, 2);
