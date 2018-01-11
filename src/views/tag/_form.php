<?php
/* @var $this yii\web\View */
/* @var $model models\Tag */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

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
		'prompt' => \Yii::t('app', 'Select'),
    ]) ?>

	<?= Html::submitButton('Submit', ['id' => 'submit-btn', 'style' => 'display: none']) ?>

<?php ActiveForm::end(); ?>

</div>
