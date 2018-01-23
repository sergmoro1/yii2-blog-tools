<style>
	.en {color: #3C763D;}
</style>

<?php
use yii\helpers\Html;
use yii\helpers\Url;
use sergmoro1\blog\Module;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = Module::t('core', 'View');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->getTitle();
?>
<div class="post-view">

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

	<div class='post-preview'>
		<?php echo $this->render('_view_head', [
			'model' => $model, 
			'read_more' => false, 
			'backend' => true,
		]); ?>

		<div class="excerpt">
			<?= $model->excerpt ?>
		</div>

		<?= $model->content ?>
		
		<?php if(mb_strlen(trim($model->resume), 'UTF-8') > 0 ): ?>
			<h3><?= Module::t('core', 'Resume'); ?></h3>
			<div class='alert alert-success'>
				<?= $model->resume ?>
			</div>
		<?php endif; ?>
		
		<?php echo $this->render('_view_foot', [
			'model' => $model, 
			'prev_next' => true, 
			'read_more' => false, 
			'backend' => true,
		]); ?>
	</div>

</div>
