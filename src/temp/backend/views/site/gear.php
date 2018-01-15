<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = \Yii::t('app', 'Gear');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
<div class="col-lg-12">

<div class="alert alert-warning" role="alert">
	Осторожно, будьте внимательны! Все символы значимы. Удаление или ввод с нарушением синтаксиса может вызвать ошибку.
</div>

<?php $form = ActiveForm::begin(['id' => 'form-gear']); ?>

	<?php echo $form->errorSummary($model); ?>

	<?= $form->field($model, 'params')
		->textArea([
			'rows' => 20,
		])->label(false)
	?>

	<?= Html::submitButton(\Yii::t('app', 'Save'), [
		'class' => 'btn btn-success', 
		'name' => 'submit-button',
	]) ?>

<?php ActiveForm::end(); ?>

</div> <!-- / .col ... -->
</div> <!-- / .row -->

