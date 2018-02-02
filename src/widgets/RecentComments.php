<?php
namespace sergmoro1\blog\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use sergmoro1\blog\Module;

use sergmoro1\blog\models\Comment;

class RecentComments extends Widget
{
    public $view = 'recentComments';
    public $title;

    public function init()
    {
        $this->title = Module::t('core', 'Comments');
        parent::init();
    }

    public function getRecentComments()
    {
        return Comment::findRecentComments(Yii::$app->params['recentCommentCount']);
    }

        public function run()
    {
        echo $this->render($this->view, [
            'title' => $this->title,
            'comments' => $this->getRecentComments(),
        ]);
    }
}
