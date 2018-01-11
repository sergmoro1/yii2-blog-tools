<?php
namespace backend\modules\blog\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use backend\modules\blog\models\Comment;

class RecentComments extends Widget
{
	public $view = 'recentComments';
	public $title;

	public function init()
	{
		$this->title = mb_strtoupper(\Yii::t('app', 'Comments'), 'UTF-8');
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
