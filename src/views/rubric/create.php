<?php
/* @var $this yii\web\View */
/* @var $model models\Rubric */

use yii\helpers\Html;
use sergmoro1\blog\Module;

$this->title = Module::t('core', 'Add');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Rubrics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="rubric-update">

	<h1><?= Html::encode($this->title) ?></h1>
	
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
