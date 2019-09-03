<?php

namespace sergmoro1\blog\tests\common\_views;

use yii\codeception\BasePage;

/**
 * Represents User form
 * @property FunctionalTester $actor
 */
class CreateUserForm extends BasePage
{
    public $route = 'user/user/update';

    public function init()
    {
        $this->route = Url::to(['/user/user/update', 'id' => 2]);
    }
    
    /**
     * @param string $name
     */
    public function fill($name, $email)
    {
        $this->actor->fillField('input[name="User[name]"]', $name);
        $this->actor->fillField('input[name="User[email]"]', $email);
        $this->actor->click('Save', '.btn-success');
    }
}
