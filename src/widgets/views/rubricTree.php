<?php
/*
 * Show rubric tree.
 */

use yii\helpers\Url;
?>

<?php if(count($rubrics) > 0): // if any rubrics were defined ?>
<div class='rubric-tree'>
    <p><?= $title; ?></p>
    <ul>
    <?php foreach($rubrics as $rubric): ?>

        <?php if($rubric->show): ?>

            <li>
                <a href="<?= Url::to(['post/index', 'rubric' => $rubric->slug]) ?>" title="<?= $rubric->name ?>">
                    <?= $rubric->getPrettyName() ?>
                </a>
                <?php if($show_post_count): ?>
                <span>(<?= $rubric->post_count ?>)</span>
                <?php endif; ?>
            </li>

        <?php endif; ?>

    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
