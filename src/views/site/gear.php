<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use sergmoro1\blog\Module;

$this->title = \Yii::t('app', 'Gear');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
<div class="col-lg-12">

<?php if($error): ?>
<div class="alert alert-danger" role="alert">
    <?= $error ?>
</div>
<?php else: ?>
<div class="alert alert-warning" role="alert">
    <?= Module::t('core', 'Be careful! All symbols are meaningful.') ?>
</div>
<?php endif; ?>

<?php
?>

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

