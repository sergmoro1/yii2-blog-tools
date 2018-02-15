<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Backend');
$this->params['noTitle'] = true;
?>
<div class="site-index">

	<div class="row">
		<div class="col-sm-12">
			<h2>Posts</h2>
			<p>
				Posts list, filtering, preview. Add pictures and photos. Russian and English parts.
			</p>
			<p>
				Nested Set rubrics, tags for searching. Chains of posts for navigation.
			</p>

			<p><?= Html::a('list &raquo;', ['blog/post/index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<h2>Comments</h2>
			<p>
				Comments can leave anyone without registration.
			</p>
			<p>
				The comment is published after approvement.
			</p>
			<p>
				Registered users can leave an answer for comments to own posts.
			</p>

			<p><?= Html::a('list &raquo;', ['blog/comment/index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<h2>Users</h2>
			<p>
				Full profile. Any number of photos.
			</p>
			<p>
				Two roles - user and admin. Your post can change only you.
			</p>
			<p>
				Registration with email confirmation.
			</p>

			<p><?= Html::a('list &raquo;', ['user/user/index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

</div>
