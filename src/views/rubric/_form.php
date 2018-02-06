<?php
/* @var $this yii\web\View */
/* @var $model models\Rubric */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="rubric-form">

<?php $form = ActiveForm::begin([
    'id' => 'rubric-form',
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-4',
            'offset' => 'col-sm-offset-4',
            'wrapper' => 'col-sm-6',
        ],
    ],
]); ?>

    <?= $form->field($model, 'parent_node')->dropdownList($model->items(), [
        'prompt' => \Yii::t('app', 'Select'),
        'disabled' => !$model->isNewRecord,
    ]) ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'show')->dropDownList([0 => 'нет', 1 => 'да'], [
        'prompt' => \Yii::t('app', 'Select'),
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= Html::submitButton('Submit', ['id' => 'submit-btn', 'style' => 'display: none;']) ?>

<?php ActiveForm::end(); ?>

</div>
