<?php
/*
 * Recent Posts widget.
 * @var $posts
 */

use yii\helpers\Url;
?>

<div class="recent-posts">
<h3>Publications</h3>
<ul>
  <?php foreach($posts as $post): ?>
  <li>
	<article class="media">
	  <img src="<?= $post->getImage('thumb') ?>" alt="<?= $post->getDescription() ?>">
	  <div class="media-body">
		<h4><?= $post->title ?></h4>
		<p><?= $post->subtitle ?></p>
		<a class="btn btn-primary" href="<?= $post->url ?>"><?= \Yii::t('app', 'Follow') ?></a>
	  </div>
	</article>
  </li>
  <?php endforeach; ?>
</ul>
</div>
