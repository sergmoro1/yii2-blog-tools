<?php
namespace sergmoro1\blog\widgets;

use yii\base\Widget;

use common\models\Post;

class RecentPosts extends Widget
{
    public $title = 'Publications';
    public $limit = 5;
    public $view = 'recentPosts';

    public function init()
    {
        $this->title = \Yii::t('app', $this->title);
        parent::init();
    }

    public function run()
    {
        echo $this->render($this->view, [
            'title' => $this->title,
            'posts' => Post::getRecentPosts($this->limit),
        ]);
    }
}
?>
