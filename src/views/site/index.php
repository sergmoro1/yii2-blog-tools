<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Backend');
$this->params['noTitle'] = true;
?>
<div class="site-index">

	<div class="row">
		<div class="col-sm-12">
			<h2 class="ru">Cтатьи</h2>
			<p class="ru">
				Список постов, фильтры, предпросмотр. Добавление картинок и фото. Русская и английская часть.
			</p>
			<p class="ru">
				Вложенные множества, метки для ускорения поиска. Цепочки постов для связывания статей по смыслу.
			</p>

			<h2 class="en">Posts</h2>
			<p class="en">
				Posts list, filtering, preview. Add pictures and photos. Russian and English parts.
			</p>
			<p class="en">
				Nested Set rubrics, tags for searching. Chains of posts for navigation.
			</p>

			<p><?= Html::a('list &raquo;', ['post/index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<h2 class="ru">Комментарии</h2>
			<p class="ru">
				Коментарий может оставить любой без регистрации.
			</p>
			<p class="ru">
				Комментарий публикуется после подтверждения.
			</p>
			<p class="ru">
				Зарегистрированные пользователи могут оставлять ответы на комментарии к собственным постам.
			</p>

			<h2 class="en">Comments</h2>
			<p class="en">
				Comments can leave anyone without registration.
			</p>
			<p class="en">
				The comment is published after approvement.
			</p>
			<p class="en">
				Registered users can leave an answer for comments to own posts.
			</p>

			<p><?= Html::a('list &raquo;', ['comment/index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<h2 class="ru">Пользователи</h2>
			<p class="ru">
				Полный профайл. Любое количество фото.
			</p>
			<p class="ru">
				Две роли - пользователь и админ. Ваши посты может изменить только автор и админ.
			</p>
			<p class="ru">
				Регистрация с подтверждением по email.
			</p>

			<h2 class="en">Users</h2>
			<p class="en">
				Full profile. Any number of photos.
			</p>
			<p class="en">
				Two roles - user and admin. Your post can change only you.
			</p>
			<p class="en">
				Registration with email confirmation.
			</p>

			<p><?= Html::a('list &raquo;', ['user/index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

</div>
