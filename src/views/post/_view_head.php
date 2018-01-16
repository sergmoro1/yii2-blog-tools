<?php
use yii\helpers\Html;
use sergmoro1\blog\Module;

use common\models\Post;
?>

<?php if(!isset($backend)): ?>
<a href="<?= $model->url; ?>" title="<?= Module::t('core', 'go to') ?>">
<?php endif; ?>

<h2 class='post-title'>
	<?= $model->getTitle() ?>
</h2>
<h3 class='post-subtitle'>
	<?= $model->subtitle ?>
</h3>

<?php if(!isset($backend)): ?>
</a>
<?php endif; ?>

<div class='post-meta'>
	<i class="fa fa-calendar"></i> <?= $model->getFullDate('created_at'); ?>
	<?php if($read_more && $thumb = $model->getImage('thumb')): ?>
		<a href="<?= $model->url ?>">
			<img src="<?= $thumb ?>" alt="<?= $model->getCurrentDescription(); ?>">
		</a>
	<?php endif; ?>
</div>
