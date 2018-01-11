<?php
/* @var $this yii\web\View */
/* @var $model models\Comment */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Answer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->author, 'url' => ['index', 'author' => $model->author]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class='col-sm-6'>

	<label class="control-label" for="comment-author">
			<?= $model->getAttributeLabel('author') ?>
	</label>
	<div class='well' id='comment-author'>
		<?= $model->author ?>
	</div>
	<label class="control-label" for="comment-content">
			<?= $model->getAttributeLabel('content') ?>
	</label>
	<div class='well' id='comment-content'>
		<?= $model->content ?>
	</div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
	
	</div>
</div>
