<?php
/* @var $this yii\web\View */
/* @var $modelmodels\Rubric */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];

if($model->model == 1) // Post
$this->params['breadcrumbs'][] = ['label' => $model->post->getTitle(), 'url' => ['post/' . $model->post->slug]];
if($model->model == 2) // Fund
$this->params['breadcrumbs'][] = ['label' => $model->fund->caption, 'url' => ['fund/' . $model->fund->slug]];

$this->params['breadcrumbs'][] = $model->author;
?>

<div class="rubric-update">

	<h1><?= Html::encode($this->title) ?></h1>
	
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
