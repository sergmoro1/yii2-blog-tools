<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \notgosu\yii2\modules\metaTag\models\MetaTag */

$this->title = Yii::t('metaTag', 'Create Meta Tag');
$this->params['breadcrumbs'][] = ['label' => Yii::t('metaTag', 'Meta Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>
