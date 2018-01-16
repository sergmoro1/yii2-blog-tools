<?php
use yii\helpers\Html;
use yii\helpers\Url;
use sergmoro1\blog\Module;

use common\models\Post;

$read_more = isset($prev) || isset($next) ? false : true;
?>

<div class='post-meta'>
	<div class='row'>
		<div class='col-xs-6'>
			<span class="fa fa-tag" aria-hidden="true" title='<?= Module::t('core', 'tags') ?>'></span>
			<?php echo implode(', ', $model->tagLinks); ?>
			<br/>
			<span class="fa fa-comment" aria-hidden="true" title='<?= Module::t('core', 'comments') ?>'></span>
			<?= Html::a(Module::t('core', 'comments'), $model->url . '#comments') . " ({$model->commentCount})"; ?>
		</div>
		<div class='col-xs-6 text-right'>
		<?php if($read_more): ?>
			<span class="read-more" data-post-id="<?= $model->id ?>"><?= Module::t('core', 'read more') ?></span><br>
			<?= Html::a(Module::t('core', 'go to'), $model->url) ?>
		<?php else: ?>
			<?= frontend\widgets\SocialLikes::widget([
				'url' => Url::to(['post/' . $model->slug], true), 
				'title' => $model->getTitle(), 
				'image' => (count($model->files) > 0 ? $model->getImage('thumb') : false),
			]) ?>
		<?php endif; ?>
		</div>
	</div>
</div>


<!-- Previous & Next -->
<?php if(!$read_more): ?>

	<a name='previous'></a>
	<?= $this->render('_view_prev_next', ['model' => $model, 'prev' => $prev, 'next' => $next]) ?>

<?php endif; ?> <!-- /Previous & Next -->
