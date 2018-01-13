<?php 
/*
 * Show ricent comments.
 */

use yii\helpers\Html;
?>

<p><?php echo $title; ?></p>
<?php if(count($comments) > 0): ?>
    <div class='post-preview'>
    <?php foreach($comments as $comment): ?>
        <div class='post-meta'>
            <?= date('d.m h:i', $comment->created_at); ?>, <?= $comment->authorLink; ?><br>
            <?= $comment->post->getTitle(); ?> 
        </div>
        &laquo;<?= Html::a(Html::encode($comment->getShortContent()), $comment->getUrl()); ?>&raquo;
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <p><?= \Yii::t('app', 'There are no comments yet.'); ?></p>
<?php endif; ?>
