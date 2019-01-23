<?php
namespace sergmoro1\blog\widgets;

use yii\base\Widget;
use sergmoro1\blog\Module;

class SocialLikes extends Widget
{
    public $viewFile = 'socialLikes';
    public $call = '';
    public $url;
    public $title;
    public $image;

    public function init() {
        parent::init();
        if($this->call === '')
            $this->call = Module::t('core', 'Share');
    }
    
    public function run()
    {
        return $this->render($this->viewFile, [
            'call' => $this->call,
            'url' => $this->url,
            'title' => $this->title,
            'image' => $this->image,
        ]);
    }
}
?>
