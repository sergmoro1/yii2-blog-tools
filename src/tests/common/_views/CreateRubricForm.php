<?php

namespace sergmoro1\blog\tests\common\_views;

use yii\codeception\BasePage;

/**
 * Represents Rubric form
 * @property FunctionalTester $actor
 */
class CreateRubricForm extends BasePage
{
    public $route = 'blog/rubric/create';

    /**
     * @param string $name
     */
    public function fill($parent_node, $position, $show, $name, $slug)
    {
        if($parent_node)
            $this->actor->fillField('input[name="Rubric[parent_node]"]', $parent_node);
        $this->actor->fillField('input[name="Rubric[position]"]', $position);
        if($show)
            $this->actor->fillField('input[name="Rubric[show]"]', $show);
        $this->actor->fillField('input[name="Rubric[name]"]', $name);
        $this->actor->fillField('input[name="Rubric[slug]"]', $slug);
        $this->actor->click('#submit-btn');
    }
}
