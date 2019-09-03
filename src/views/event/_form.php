<?php
/* @var $this yii\web\View */
/* @var $model common\models\Event */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<div class="event-form">

<?php $form = ActiveForm::begin([
	'id' => 'event-form',
	'layout' => 'horizontal',
	'validationUrl' => Url::toRoute(['event/validate']),		
	'fieldConfig' => [
		'horizontalCssClasses' => [
			'label' => 'col-sm-4',
			'offset' => 'col-sm-offset-4',
			'wrapper' => 'col-sm-6',
		],
	],
]); ?>

    <?= $form->field($model, 'post_id')->dropdownList($model->getEventsPosts(), [
		'disabled' => !$model->isNewRecord,
		'prompt' => \Yii::t('app', 'Select'),
	]); ?>

    <?= $form->field($model, 'begin_date') ?>

    <?= $form->field($model, 'end_date') ?>

    <?= $form->field($model, 'responsible') ?>

	<?= Html::submitButton('Submit', ['id' => 'submit-btn', 'style' => 'display: none']) ?>

<?php ActiveForm::end(); ?>

</div>
