<?php
/* @var $this yii\web\View */
/* @var $model models\Tag */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use sergmoro1\blog\Module;
?>

<div class="tag-form">

<?php $form = ActiveForm::begin([
	'id' => 'tag-form',
	'layout' => 'horizontal',
	'enableAjaxValidation' => true,
	'validationUrl' => Url::toRoute(['tag/validate']),		
	'fieldConfig' => [
		'horizontalCssClasses' => [
			'label' => 'col-sm-4',
			'offset' => 'col-sm-offset-4',
			'wrapper' => 'col-sm-6',
		],
	],
]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'show')->dropDownList([0 => 'нет', 1 => 'да'], [
		'prompt' => Module::t('core', 'Select'),
    ]) ?>

	<?= Html::submitButton(Module::t('core', 'Submit'), ['id' => 'submit-btn']) ?>

<?php ActiveForm::end(); ?>

</div>
