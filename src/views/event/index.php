<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;

use sergmoro1\blog\Module;
use sergmoro1\blog\models\Rubric;
use sergmoro1\lookup\models\Lookup;

$this->registerJs('var popUp = {"id": "event", "actions": ["update"]};', yii\web\View::POS_HEAD);
sergmoro1\modal\assets\PopUpAsset::register($this);

$this->title = Module::t('core', 'Events');
$this->params['breadcrumbs'][] = $this->title;;

echo Modal::widget([
	'id' => 'event-win',
	'toggleButton' => false,
	'header' => $this->title,
	'footer' => 
        '<button type="button" class="btn btn-default" data-dismiss="modal">'. Module::t('core', 'Cancel') .'</button>' . 
        '<button type="button" class="btn btn-primary">'. Module::t('core', 'Save') .'</button>', 
]);

?>
<div class="event-index">

<div class='row'>
<div class='col-sm-12'>

	<p>
		<?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Module::t('core', 'Add'), ['create'], [
			'id' => 'event-add',
			'data-toggle' => 'modal',
			'data-target' => '#event-win',
			'class' => 'btn btn-success',
		]) ?>
	</p>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
				'attribute' => 'id',
				'options' => ['style' => 'width:4%;'],
			],
			[
				'header' => 'Заголовок',
				'format' => 'html',
				'value' => function($data) {
					return $data->post->getTitle(true) . ' <small>(' . $data->post->subtitle . ')</small>';
				},
				'options' => ['style' => 'width:20%;'],
			],
			[
				'header' => 'Краткое описание',
				'format' => 'html',
				'value' => function($data) {
					return $data->post->excerpt;
				},
				'options' => ['style' => 'width:40%;'],
			],
			'responsible',
            [
				'attribute' => 'begin',
				'value' => function($data) {
					return date('d.m.y', $data->begin);
				},
			],
            [
				'attribute' => 'end',
				'value' => function($data) {
					return date('d.m.y', $data->end);
				},
			],
            [
				'class' => 'yii\grid\ActionColumn',
				'options' => ['style' => 'width:6%;'],
				'template' => '{update} {delete}',
				'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            \Yii::$app->params['icons']['pencil'], 
                            $url, [
                                'class' => 'update',
                                'data-toggle' => 'modal',
                                'data-target' => '#event-win',
                            ]
                        );
                    },
				],
			],
        ],
    ]); ?>
    </div>
    
</div>

</div> <!-- ./row -->
</div> <!-- ./rubric-index -->
