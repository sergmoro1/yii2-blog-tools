<style>
p.answer {
	text-align: right;
	color: #3C763D;
}
</style>
<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;

use sergmoro1\lookup\models\Lookup;

$this->title = Yii::t('app', 'Comments');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

echo Modal::widget([
	'id' => 'comment-win',
	'size' => Modal::SIZE_LARGE,
	'toggleButton' => false,
	'header' => $this->title,
	'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>' . 
		Html::button('Сохранить', ['class' => 'btn btn-primary', 'onclick' => '$(".comment-form #submit-btn").click()']),
]);
?>
<div class="comment-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
				'attribute' => 'id',
				'options' => ['style' => 'width:80px;'],
			],
			[
				'attribute' => 'model',
				'filter' => Lookup::items('CommentFor'),
				'value' => function($data) {
					return Lookup::item('CommentFor', $data->model);
				}
			],
            [
				'header' => 'Title',
				'format' => 'html',
				'value' => function($data) {
					return $data->post->getTitleLink();
				}
			],
			'author',
			'email',
            [
				'attribute' => 'content',
				'options' => ['style' => 'width:50%;'],
				'value' => function($data) {
					// declare params for indention in a row,
					// using View class internal variable - params
					if(!isset($this->params['thread']))
						$this->params['thread'] = false;
					if($this->params['thread'] == $data->thread) 
						$this->params['indention'] .= '--';
					else {
						$this->params['thread'] = $data->thread;
						$this->params['indention'] = '';
					}
					return $this->params['indention'] . ' ' . $data->content;
				}
			],
			[
				'attribute' => 'status',
				'filter' => Lookup::items('CommentStatus'),
				'value' => function($data) {
					return Lookup::item('CommentStatus', $data->status);
				}
			],
            [
				'attribute' => 'created_at',
				'value' => function($data) {
					return date('d.m.y', $data->created_at);
				},
				'options' => ['style' => 'width:80px;'],
			],
            [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{reply}{update}{delete}', 
				'options' => ['style' => 'width:8%;'],
				'buttons' => [
					'reply' => function ($url, $model) {
						return Html::a(
							'<i class="fa fa-reply"></i>', 
							$url, [
								'data-toggle' => 'modal',
								'data-target' => '#comment-win',
								'onclick' => "$('#comment-win .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
							]
						);
					},
					'update' => function ($url, $model) {
						return Html::a(
							'<i class="fa fa-pencil"></i>', 
							$url, [
								'data-toggle' => 'modal',
								'data-target' => '#comment-win',
								'onclick' => "$('#comment-win .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
							]
						);
					},
				],

			],
        ],
    ]); ?>

</div>
