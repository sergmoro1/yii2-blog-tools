<?php
/* @var $this yii\web\View */
/* @var $model models\Comment */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use sergmoro1\lookup\models\Lookup;

?>

<div class="comment-form">

<?php $form = ActiveForm::begin([
	'id' => 'comment-form',
	'layout' => 'horizontal',
	'fieldConfig' => [
		'horizontalCssClasses' => [
			'label' => 'col-sm-4',
			'offset' => 'col-sm-offset-4',
			'wrapper' => 'col-sm-6',
		],
	],
]); ?>

	<?php echo $form->errorSummary($model); ?>

	<?= $form->field($model, 'author')
		->textInput([
			'maxlength' => 128, 
		]
	) ?>

	<?= $form->field($model, 'content')
		->textArea([
			'rows' => 6,
			'maxlength' => 512,
		]
	) ?>

    <?= $form->field($model, 'status')->dropdownList(
		Lookup::items('CommentStatus')
	) ?>

	<?= Html::submitButton(Yii::t('app', 'Submit'), ['id' => 'submit-btn', 'style' => 'display: none']) ?>

    <?php ActiveForm::end(); ?>

</div>
