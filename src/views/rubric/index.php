<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use sergmoro1\blog\Module;

use sergmoro1\lookup\models\Lookup;

$this->registerJs('var popUp = {"id": "#rubric-win", "action": ["update"]};', yii\web\View::POS_HEAD);
sergmoro1\blog\assets\PopUpAsset::register($this);

$this->title = Module::t('core', 'Rubrics');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Posts'), 'url' => ['post/index']];
$this->params['breadcrumbs'][] = $this->title;

echo Modal::widget([
    'id' => 'rubric-win',
    'toggleButton' => false,
    'header' => $this->title,
    'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">'. Module::t('core', 'Cancel') .'</button>' . 
        Html::button(Module::t('core', 'Save'), ['class' => 'btn btn-primary', 'onclick' => '$(".rubric-form #submit-btn").click()']),
]);

?>

<div class="rubric-index">

<div class='row'>
<div class='col-sm-8'>
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Module::t('core', 'Add'), ['create'], [
            'id' => 'rubric-add',
            'data-toggle' => 'modal',
            'data-target' => '#rubric-win',
            'class' => 'btn btn-success',
            'onclick' => "$('#rubric-win .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
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
                            '<span class="glyphicon glyphicon-pencil"></span>', 
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
