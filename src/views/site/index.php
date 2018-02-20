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
                Posts with <code>title</code>, <code>subtitle</code>, <code>excerpt</code>, <code>content</code> and <code>resume</code>. 
                Photos & files uploading. Photos chain for carousel slider.
            </p>
            <p>
                Nested Set <code>rubrics</code>, <code>tags</code> for searching. Chains of posts for navigation.
            </p>
            <p>One or more Authors for the Post.</p>

            <p><?= Html::a('list &raquo;', ['/blog/post/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h2>Comments</h2>
            <p>
                Comments can leave anyone without registration.
                The comment is published after approvement.
            </p>
            <p>
                Registered users can leave an answer for comments to own posts.
            </p>

            <p><?= Html::a('list &raquo;', ['/blog/comment/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h2>Users</h2>
            <p>
                Short profile, avatar.
                Two roles - <code>user</code> and <code>admin</code>. Your post can change only you.
            </p>
            <p>
                Registration with email confirmation.
            </p>

            <p><?= Html::a('list &raquo;', ['/user/user/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

</div>
