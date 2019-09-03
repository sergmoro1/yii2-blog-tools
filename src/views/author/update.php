<?php

use yii\helpers\Html;
use sergmoro1\blog\Module;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Module::t('core', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Authors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="author-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
