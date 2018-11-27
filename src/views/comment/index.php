<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use sergmoro1\blog\Module;

use sergmoro1\lookup\models\Lookup;

$this->registerJs('var popUp = {"id": "comment", "actions": ["reply", "update"]};', yii\web\View::POS_HEAD);
sergmoro1\modal\assets\PopUpAsset::register($this);

$this->title = Module::t('core', 'Comments');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

echo Modal::widget([
    'id' => 'comment-win',
    'size' => Modal::SIZE_LARGE,
    'toggleButton' => false,
    'header' => $this->title,
    'footer' => 
        '<button type="button" class="btn btn-default" data-dismiss="modal">'. Module::t('core', 'Cancel') .'</button>' . 
        '<button type="button" class="btn btn-primary">'. Module::t('core', 'Save') .'</button>', 
]);
?>
<div class='comment-index table-responsive'>

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
                'header' => Module::t('core', 'Title'),
                'format' => 'html',
                'value' => function($data) {
                    return $data->post->getTitleLink();
                }
            ],
            [
                'attribute' => 'user_id',
                'value' => function($data) {
                    return $data->author->name;
                }
            ],
            [
                'header' => 'email',
                'value' => function($data) {
                    return $data->author->email;
                }
            ],
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
                    return $this->params['indention'] . ' ' . $data->getPartContent(100);
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
                        if(!$model->last || $model->user_id == \Yii::$app->user->id)
                            return '';
                        return Html::a(
                            '<span class="glyphicon glyphicon-share-alt" title="'. Module::t('core', 'Reply') .'"></span>',                         
                            $url, [
                                'class' => 'reply',
                                'data-toggle' => 'modal',
                                'data-target' => '#comment-win',
                            ]
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                            \Yii::$app->params['icons']['pencil'],
                            $url, [
                                'class' => 'update',
                                'data-toggle' => 'modal',
                                'data-target' => '#comment-win',
                            ]
                        );
                    },
                ],

            ],
        ],
    ]); ?>

</div>
