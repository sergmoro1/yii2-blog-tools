<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use sergmoro1\blog\Module;


use sergmoro1\lookup\models\Lookup;

$this->title = Module::t('core', 'Authors');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="post-index">

<div class='row'>
<div class='col-sm-8'>

    <p>
        <?= Html::a(\Yii::$app->params['icons']['plus'] . ' ' . Module::t('core', 'Add'), ['create'], [
            'id' => 'author-add',
            'class' => 'btn btn-success',
        ]) ?>
    </p>

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
                'header' => Module::t('core', 'Thumb'),
                'format' => 'html',
                'value' => function($data) {
                    return Html::img($data->getImage('thumb'), ['class' => 'img-responsive']);
                }
            ],
            'name',
            [
                'header' => Module::t('core', 'Frequency'),
                'value' => function($data) {
                    return $data->frequency;
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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}', 
                'options' => ['style' => 'width:6%;'],
            ],
        ],
    ]); ?>

</div>

<div class='col-sm-4'>
    <?= $this->render('help') ?>
</div>

</div> <!-- ./row -->
</div> <!-- ./rubric-index -->
