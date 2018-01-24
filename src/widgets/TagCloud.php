<?php
namespace sergmoro1\blog\widgets;

use yii\helpers\Html;
use yii\base\Widget;
use sergmoro1\blog\models\Tag;

use common\models\Post;

class TagCloud extends Widget
{
	public $title = 'Tags';
	public $use_weight = false;
	public $linkClass = '';
	public $view = 'tagCloud';

	private $items;
	
	public function init()
	{
		$this->title = \Yii::t('app', $this->title);
		if(Post::getPublishedPostCount() > 0 && ($tags = Tag::findTagWeights(\Yii::$app->params['tagCloudCount'])))
		{
			$this->items = [];
			$a = $this->use_weight ? ['style'=>"font-size:{$weight}pt"] : [];
			$a['class'] = $this->linkClass;
			
			foreach($tags as $tag=>$weight)
			{
				$this->items[] = Html::a(
					mb_convert_case(Html::encode($tag), MB_CASE_TITLE, 'UTF-8'), 
					['post/tag/' . str_replace(' ', '-', $tag)], 
					$a
				);
			}
		}
		parent::init();
	}
	public function run()
	{
		return $this->render($this->view, [
			'title' => $this->title,
			'items' => $this->items,
		]);
	}
}
