<style>
	.en {color: #3C763D;}
</style>

<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = Yii::t('app', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->getTitle();
?>
<div class="post-view">

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

	<div class='post-preview'>
		<?php echo $this->render('@frontend/views/post/_view_head', [
			'model' => $model, 
			'read_more' => false, 
			'backend' => true,
		]); ?>

		<div class="excerpt">
			<?= $model->excludeByLanguage('excerpt'); ?>
		</div>

		<?= $model->excludeByLanguage('content'); ?>
		
		<?php if(mb_strlen(trim($model->resume), 'UTF-8') > 0 ): ?>
			<h3><?= \Yii::t('app', 'Resume'); ?></h3>
			<div class='alert alert-success'>
				<?= $model->excludeByLanguage('resume'); ?>
			</div>
		<?php endif; ?>
		
		<?php echo $this->render('@frontend/views/post/_view_foot', [
			'model' => $model, 
			'prev_next' => true, 
			'read_more' => false, 
			'backend' => true,
		]); ?>
	</div>

</div>
