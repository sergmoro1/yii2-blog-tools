<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('metaTag', 'Meta Tags');
$this->params['breadcrumbs'][] = $this->title;
?>

<div>

	<p>
		<?= Html::a(Yii::t('metaTag', 'Create Meta Tag'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
		'columns' => [
			'id',
			'name',
			'is_http_equiv:boolean',
			'default_value:ntext',
			'description',
			'is_active:boolean',
			'position',

			[
			    'class' => 'yii\grid\ActionColumn',
			    'template' => '{update} {delete}',
			],
		],
	]); ?>
</div>
