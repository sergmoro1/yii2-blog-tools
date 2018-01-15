<?php
use yii\helpers\Html;
use sergmoro1\blog\Module;

/* @var $this yii\web\View */
/* @var $model models\Tag */

$this->title = Module::t('core', 'Update');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="event-update">
	<h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
