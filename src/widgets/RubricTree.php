<?php
namespace sergmoro1\blog\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use sergmoro1\blog\Module;

use sergmoro1\blog\models\Rubric;

class RubricTree extends Widget
{
    public $viewFile = 'rubricTree';
    public $show_post_count = false;
    public $title;

    public function init()
    {
        $this->title = $this->title
            ? $this->title
            : Module::t('core', 'Rubrics');
        parent::init();
    }

    public function getRubrics()
    {
        $rubrics = Rubric::find()
            ->where('id > 1 AND rubric.show')
            ->orderBy('position asc')
            ->all();
        return $rubrics;
    }

    public function run()
    {
        echo $this->render($this->viewFile, [
            'show_post_count' => $this->show_post_count,
            'title' => $this->title,
            'rubrics' => $this->getRubrics(),
        ]);
    }
}

