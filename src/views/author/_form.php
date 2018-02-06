<?php
/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sergmoro1\uploader\widgets\Byone;
use sergmoro1\blog\Module;

?>

<?php $form = ActiveForm::begin(); ?>
<div class='row'>
	<div class="col-lg-8">
		<?= Byone::widget([
			'model' => $model,
			'appendixView' => '/author/appendix',
			'cropAllowed' => true,
			'draggable' => true,
		]) ?>

		<?= $form->field($model, 'name')
			->textInput(['maxlength' => true])
		?>

		<div class="form-group">
			<?= Html::submitButton(Module::t('core', 'Save'), [
				'class' => 'btn btn-success',
			]) ?>
		</div>
	</div>

	<div class="col-lg-4">


	</div>
</div>

<?php ActiveForm::end(); ?>
