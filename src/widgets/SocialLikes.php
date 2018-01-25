<?php
namespace sergmoro1\blog\widgets;

use yii\base\Widget;

class SocialLikes extends Widget
{
    public $view = 'socialLikes';
    public $url;
    public $title;
    public $image;
    
    public function run()
    {
        return $this->render($this->view, [
            'url' => $this->url,
            'title' => $this->title,
            'image' => $this->image,
        ]);
    }
}
?>
