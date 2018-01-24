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

<div class="post-index">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Module::t('core', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                'attribute' => 'title',
                'format' => 'html',
                'value' => function($data) {
                    return $data->getTitleLink(false) . ' <small>' . $data->subtitle . '</small>';
                }
            ],
            'tags:ntext',
            [
                'attribute' => 'rubric',
                'filter' => Rubric::items(),
                'value' => function($data) {
                    return Rubric::findOne($data->rubric)->name;
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
                'attribute' => 'author_id',
                'value' => function($data) {
                    return common\models\User::findOne($data->author_id)->name;
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
