<?php
/* @var $this yii\web\View */
/* @var $model models\Rubric */

use yii\helpers\Html;

$this->title = \Yii::t('app', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rubrics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="rubric-update">

	<h1><?= Html::encode($this->title) ?></h1>
	
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
