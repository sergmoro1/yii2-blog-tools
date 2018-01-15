<?php
namespace sergmoro1\blog\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use sergmoro1\blog\Module;

use sergmoro1\blog\models\Rubric;

class RubricTree extends Widget
{
    public $view = 'rubricTree';
    public $title = 'Rubrics';

    public function init()
    {
        $this->title = mb_strtoupper(Module::t('core', $this->title), 'UTF-8');
        parent::init();
    }

    public function getBranches()
    {
        $rubrics = Rubric::find()
            ->select('id, name, level')
            ->where('id > 1 AND rubric.show')
            ->orderBy('position asc')
            ->all();
        foreach($rubrics as $rubric)
            $rubric->post_count = \common\models\Post::find()
                ->where(['rubric' => $rubric->id])
                ->count();
        return $rubrics;
    }

    public function run()
    {
        echo $this->render($this->view, [
            'title' => $this->title,
            'rubrics' => $this->getBranches(),
        ]);
    }
}

