<?php

namespace sergmoro1\blog\tests\common\_pages;

use yii\codeception\BasePage;

/**
 * Represents loging page
 * @property FunctionalTester $actor
 */
class LoginPage extends BasePage
{
    public $route = 'user/site/login';

    /**
     * @param string $name
     * @param string $password
     */
    public function login($name, $password)
    {
        $this->actor->fillField('input[name="LoginForm[name]"]', $name);
        $this->actor->fillField('input[name="LoginForm[password]"]', $password);
        $this->actor->click('login-button');
    }
}
