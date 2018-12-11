<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;

use sergmoro1\blog\Module;
use sergmoro1\lookup\models\Lookup;

$this->registerJs('var popUp = {"id": "rubric", "actions": ["update"]};', yii\web\View::POS_HEAD);
sergmoro1\modal\assets\PopUpAsset::register($this);

$this->title = Module::t('core', 'Rubrics');
$this->params['breadcrumbs'][] = $this->title;

echo Modal::widget([
    'id' => 'rubric-win',
    'toggleButton' => false,
    'header' => $this->title,
    'footer' => 
        '<button type="button" class="btn btn-default" data-dismiss="modal">'. Module::t('core', 'Cancel') .'</button>' . 
        '<button type="button" class="btn btn-primary">'. Module::t('core', 'Save') .'</button>', 
]);

?>

<div class="rubric-index">

<div class='row'>
<div class='col-sm-8'>
    <p>
        <?= Html::a(\Yii::$app->params['icons']['plus'] . ' ' . Module::t('core', 'Add'), ['create'], [
            'id' => 'rubric-add',
            'data-toggle' => 'modal',
            'data-target' => '#rubric-win',
            'class' => 'btn btn-success',
        ]) ?>
    </p>

    <div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            'position',
            [
                'attribute' => 'show',
                'format' => 'html',
                'value' => function($data) {
                    $show = true;
                    foreach($data->parents()->all() as $parent)
                        $show = $show && $parent->show;
                    return $data->show && $show ? '+' : '-';
                }
            ],
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function($data) {
                    return $data->getPrettyName(true) . ' (<small>' . $data->slug . '</small>)';
                }
            ],
            [
                'attribute' => 'post_count',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['style' => 'width:50px;'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            \Yii::$app->params['icons']['pencil'], 
                            $url, [
                                'class' => 'update',
                                'data-toggle' => 'modal',
                                'data-target' => '#rubric-win',
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
</div> <!-- ./rubric-index -->
