<?php

namespace sergmoro1\blog\tests\common\_views;

use yii\codeception\BasePage;

/**
 * Represents Author form
 * @property FunctionalTester $actor
 */
class CreateAuthorForm extends BasePage
{
    public $route = 'blog/author/create';

    /**
     * @param string $name
     */
    public function fill($name)
    {
        $this->actor->fillField('input[name="Author[name]"]', $name);
        $this->actor->click('Save', '.btn-success');
    }
}
