<?php
/*
 * Post that founded by slug,
 * with JSON content to put values to right places.
 */

namespace sergmoro1\blog\widgets;

use Yii;
use yii\base\Widget;
use common\models\Post;

class ShowJson extends Widget
{
    public $basePath = '@app/widgets/views/';
    public $view = false;
    public $slug;
    public $addons = null;
    
    public function init()
    {
        if(!$this->view)
            $this->view = str_replace('-', '_', $this->slug);
        parent::init();
    }
    
    public function run()
    {
        if(($post = Post::findOne(['slug' => $this->slug])) &&
            $post->status <> Post::STATUS_ARCHIVED)
        {
            $json = json_decode(strip_tags(str_replace(' "=""', '', $post->excerpt)));
            if(json_last_error() == JSON_ERROR_NONE)
                echo $this->render($this->basePath . $this->view, [
                    'post' => $post,
                    'json' => $json,
                    'addons' => $this->addons,
                ]);
            else
                echo 'Invalid JSON definition - ' . json_last_error_msg();
        }
    }
}
?>
