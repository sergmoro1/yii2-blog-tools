<style>
	.en {color: #556b2f;}
</style>
<?php
/* @var $this UserController */
/* @var $model Tag */

use yii\helpers\Html;
use yii\helpers\Url;

use common\models\Lookup;

$this->params['breadcrumbs'] = [
	['label' => \Yii::t('app', 'Users'), 'url' => ['user/index']],
	$model->name,
];
?>

<div class='row'>
	<div class='col-sm-12'>
		<p>
			<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
					'method' => 'post',
				],
			]) ?>
		</p>
	</div>
    <!-- User info -->
	<div class='col-sm-8'>
		<h3><?= \Yii::t('app', 'Credentials') ?></h3>
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

