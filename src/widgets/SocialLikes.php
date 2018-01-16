<?php
namespace sergmoro1\blog\widgets;

use \Yii;
use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;

class SocialLikes extends Widget
{
	public $url;
	public $title;
	public $image;
	
    public function run()
    {
		return $this->render('socialLikes', [
			'url' => $this->url,
			'title' => $this->title,
			'image' => $this->image,
		]);
	}
}
?>
