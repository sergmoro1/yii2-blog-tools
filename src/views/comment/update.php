<?php
/* @var $this yii\web\View */
/* @var $model models\Comment */

use yii\helpers\Html;
use sergmoro1\blog\Module;

$this->title = Module::t('core', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];

if($model->model == 1) // Post
$this->params['breadcrumbs'][] = ['label' => $model->post->getTitle(), 'url' => ['post/' . $model->post->slug]];

$this->params['breadcrumbs'][] = $model->author->name;
?>

<div class="comment-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
