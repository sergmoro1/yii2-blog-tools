<?php
namespace sergmoro1\blog\widgets;

use yii\base\Widget;

use sergmoro1\blog\Module;
use common\models\Post;

class RecentPosts extends Widget
{
    public $viewFile = 'recentPosts';
    public $title;
    public $limit = 3;
    public $slug = false; // rubric slug
    public $tag = false;

    public function init()
    {
        $this->title = $this->title
            ? $this->title
            : Module::t('core', 'Recent Posts');
        parent::init();
    }

    public function run()
    {
        echo $this->render($this->viewFile, [
            'title' => $this->title,
            'posts' => Post::getRecentPosts($this->limit, $this->slug, $this->tag),
        ]);
    }
}
?>
