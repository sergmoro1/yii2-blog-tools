<?php
/* @var $this yii\web\View */
/* @var $model models\Rubric */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>

<div class="rubric-form">

<?php $form = ActiveForm::begin([
    'id' => 'rubric-form',
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['rubric/validate']),        
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-4',
            'offset' => 'col-sm-offset-4',
            'wrapper' => 'col-sm-6',
        ],
    ],
]); ?>

    <?= $form->field($model, 'node_id')->dropdownList($model->items(), [
        'prompt' => \Yii::t('app', 'Select'),
    ]) ?>

    <?= $form->field($model, 'type')->inline(true)->radioList($model->getTypeList()) ?>

    <?= $form->field($model, 'show')->dropDownList([0 => 'нет', 1 => 'да'], [
        'prompt' => \Yii::t('app', 'Select'),
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
    <?= Html::activeHiddenInput($model, '_slug'); ?>

    <?= Html::submitButton('Submit', ['id' => 'submit-btn', 'style' => 'display: none;']) ?>

<?php ActiveForm::end(); ?>

</div>
