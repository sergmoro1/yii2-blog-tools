<?php
/**
 * Social share buttons widget.
 * Only simple html link can be used.
 * 
 * config/params.php
 * 
 * 'socialLikes' => [
 *   'vk' => ['resource' => 'http://vk.com/share.php?url={url}&title={title}&description={description}&image={image}', 'icon' => 'fab fa-vk'],
 *   'odnoklassniki' => ['resource' => 'https://connect.ok.ru/offer?url={url}&title={title}&description={description}&imageUrl={image}', 'icon' => 'fab fa-odnoklassniki'],
 *   'google+' => ['resource' => 'https://plusone.google.com/_/+1/confirm?hl=en&url={url}', 'icon' => 'fab fa-google-plus-g'],
 * ],
 */

namespace sergmoro1\blog\widgets;

use yii\base\Widget;
use sergmoro1\blog\Module;

class SocialLikes extends Widget
{
    public $viewFile = 'socialLikes';
    public $call = '';
    public $attributes;
    
    public $socialLikes;

    public function init() {
        parent::init();
        if($this->call === '')
            $this->call = Module::t('core', 'Share');
        // Prepare resources
        $this->socialLikes = \Yii::$app->params['socialLikes'];
        foreach($this->socialLikes as $i => $social) { 
            $r = $social['resource'];
            foreach($this->attributes as $name => $value)
                $r = str_replace('{'.$name.'}', $value, $r);
            $this->socialLikes[$i]['resource'] = $r;
        }
    }
    
    public function run()
    {
        return $this->render($this->viewFile, [
            'tagTitle' => Module::t('core', 'Share link on'), 
            'call' => $this->call,
            'socialLikes' => $this->socialLikes,
        ]);
    }
}
?>
