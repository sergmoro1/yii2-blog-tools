<?php
/* @var $this yii\web\View */
/* @var $model common\models\Event */

use yii\helpers\Html;
use sergmoro1\blog\Module;

$this->title = Module::t('core', 'Add');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="event-create">

	<h1><?= Html::encode($this->title) ?></h1>
	
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
