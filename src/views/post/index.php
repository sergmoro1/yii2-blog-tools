<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use sergmoro1\blog\Module;

use sergmoro1\lookup\models\Lookup;
use sergmoro1\blog\models\Rubric;

$this->title = Module::t('core', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
	<?= Html::a(\Yii::$app->params['icons']['plus'] . ' ' . Module::t('core', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>
</p>

<div class='post-index table-responsive'>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'options' => ['class' => false],
        'columns' => [
            'id',
            'slug',
            [
                'attribute' => 'title',
                'format' => 'html',
                'value' => function($data) {
                    return $data->getTitleLink() . ' <small>' . $data->getSubtitle() . '</small>';
                }
            ],
            'tags:ntext',
            [
                'attribute' => 'rubric_id',
                'filter' => Rubric::items(),
                'value' => function($data) {
                    return $data->getRubric()->name;
                }
            ],
            [
                'attribute' => 'status',
                'filter' => Lookup::items('PostStatus'),
                'value' => function($data) {
                    return Lookup::item('PostStatus', $data->status);
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($data) {
                    return date('d.m.y', $data->created_at);
                },
                'options' => ['style' => 'width:9%;'],
            ],
            [
                'attribute' => 'user_id',
                'value' => function($data) {
                    return $data->user->username;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}', 
                'options' => ['style' => 'width:6%;'],
            ],
        ],
    ]); ?>

</div>
