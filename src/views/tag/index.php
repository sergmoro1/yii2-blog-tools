<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use sergmoro1\blog\Module;

$this->registerJs('var popUp = {"id": "#tag-win", "actions": ["update"]};', yii\web\View::POS_HEAD);
sergmoro1\blog\assets\PopUpAsset::register($this);

$this->title = Module::t('core', 'Tags');

echo Modal::widget([
    'id' => 'tag-win',
    'toggleButton' => false,
    'header' => $this->title,
    'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">'. Module::t('core', 'Cancel') .'</button>' . 
        Html::button(Module::t('core', 'Save'), ['class' => 'btn btn-primary', 'onclick' => '$(".tag-form #submit-btn").click()']),
]);

?>

<div class="tag-index">

<div class='row'>
<div class='col-sm-8'>
    
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
                'attribute' => 'show',
                'format' => 'html',
                'value' => function($data) {
                    return $data->show ? '+' : '-';
                }
            ],
            'name',
            'frequency',
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['style' => 'width:10%;'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            $url, [
                                'class' => 'update',
                                'data-toggle' => 'modal',
                                'data-target' => '#tag-win',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
    </div>

</div>

<div class='col-sm-4'>
    <?= $this->render('help') ?>
</div>

</div> <!-- ./row -->
</div> <!-- ./tag-index -->
