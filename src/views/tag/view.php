<style>
    .en {color: #556b2f;}
</style>
<?php
/* @var $this UserController */
/* @var $model Tag */

use yii\helpers\Html;
use yii\helpers\Url;
use sergmoro1\blog\Module;

use common\models\Lookup;

$this->params['breadcrumbs'] = [
    ['label' => Module::t('core', 'Users'), 'url' => ['user/index']],
    $model->name,
];
?>

<div class='row'>
    <div class='col-sm-12'>
        <p>
            <?= Html::a(Module::t('core', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Module::t('core', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Module::t('core', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    </div>
    <!-- User info -->
    <div class='col-sm-8'>
        <h3><?= \Module::t('core', 'Credentials') ?></h3>
        <p><b><?= $model->getAttributeLabel('name') ?></b></p>
        <p><?= $model->name ?></p>

        <p><b><?= $model->getAttributeLabel('email') ?></b></p>
        <p><?= $model->email ?></p>

        <p><b><?= $model->getAttributeLabel('group') ?></b></p>
        <p><?= Lookup::item('UserGroup', $model->group) ?></p>
        
        <p><b><?= $model->getAttributeLabel('status') ?></b></p>
        <p><?= Lookup::item('UserStatus', $model->status) ?></p>
    </div>
</div> <!-- / .row -->

