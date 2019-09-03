<?php
/**
 * Application configuration shared by all applications and test types
 */
return [
    'language' => 'en-US',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'fixtureDataPath' => '@vendor/sergmoro1/yii2-blog-tools/src/tests/common/fixtures/data',
            'templatePath' => '@vendor/sergmoro1/yii2-blog-tools/src/tests/common/templates/fixtures',
            'namespace' => 'sergmoro1\blog\tests\common\fixtures',
        ],
    ],
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=yii2_advanced_tests',
        ],
        'mailer' => [
            'useFileTransport' => true,
            'viewPath' => '@vendor/sergmoro1/yii2-user/src/mail',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];
