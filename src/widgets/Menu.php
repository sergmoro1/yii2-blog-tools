<?php
namespace sergmoro1\blog\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class Menu extends Widget
{
	public $items;
	public $view = 'menu';

    public function run()
    {
		echo $this->render($this->view, [
			'items' => $this->items,
		]);
	}
}

