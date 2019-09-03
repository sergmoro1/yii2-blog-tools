<?php

namespace sergmoro1\blog\tests\common\_pages;

use yii\codeception\BasePage;

/**
 * Represents Signup page
 * @property FunctionalTester $actor
 */
class SignupPage extends BasePage
{
    public $route = 'user/site/signup';

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function signup($name, $email, $password)
    {
        $this->actor->fillField('input[name="SignupForm[name]"]', $name);
        $this->actor->fillField('input[name="SignupForm[email]"]', $email);
        $this->actor->fillField('input[name="SignupForm[password]"]', $password);
        $this->actor->click('signup-button');
    }
}
