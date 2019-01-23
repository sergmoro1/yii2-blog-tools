<?php
/**
 * 
 */

use yii\helpers\Html;

$indention = '';
$thread = $comments[0]->thread;
$first = true;
?>

<!-- Comments -->
<?php foreach($comments as $comment): ?>
    <?php 
        if($first)
            $first = false;
        else {
            if($thread == $comment->thread)
                $indention .= '--';
            else {
                $indention = '';
                $thread = $comment->thread;
            }
        }
    ?>
    <!-- Comment -->
    <li class="comment">
        <span class="indention"><?= $indention ?></span>
        <?php if($image = $comment->author->getImage('thumb')): ?> 
            <img class="avatar-medium img-top" src="<?=  $image ?>">
        <?php else: ?>
            <i class="fas fa-user-circle fa-2x img-top"></i>
        <?php endif; ?>
        <span class="content"><?= $comment->content ?>
            <span class="avatar-name"><?= str_replace(' ', '', $comment->author->name) ?></span>
        </span>
        
        <!-- Reply button if comment can be answered -->
        <?php if($comment->canBeAnswered()): ?>
            <div class="text-right reply-btn-block">
                <a href="#leave-comment" data-comment-thread="<?= $comment->thread ?>" class="<?= $replyButtonClass ?> reply-btn">
                    <i class="fa fa-reply" title="<?= \Yii::t('app', 'Reply') ?>"></i>
                </a>
            </div>
        <?php endif; ?>
    </li>
<?php endforeach; ?>

