<?php
/* @var $this yii\web\View */
/* @var $model models\Comment */

use yii\helpers\Html;
use sergmoro1\blog\Module;

$this->title = Module::t('core', 'Reply');
$this->params['breadcrumbs'][] = ['label' => Module::t('core', 'Posts'), 'url' => ['index']];

if($comment->model == 1) // Post
$this->params['breadcrumbs'][] = ['label' => $comment->post->getTitle(), 'url' => ['post/' . $comment->post->slug]];
if($comment->model == 2) // Fund
$this->params['breadcrumbs'][] = ['label' => $comment->fund->caption, 'url' => ['fund/' . $comment->fund->slug]];

$this->params['breadcrumbs'][] = $comment->author;
?>

<div class="comment-reply">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="row">
        <div class="form-group">
            <label class="control-label col-sm-4 text-right" for="comment">
                <?= $comment->author . ' ' . Module::t('core', 'wrote') ?>
            </label>
            <div class="col-sm-6" id="comment">
                <div class="well">
                    <?= $comment->content ?>
                </div>
            </div>
        </div>
    </div>
    
    <?= $this->render('_form', [
        'model' => $model,
        //'comment' => $model,
    ]) ?>

</div>

