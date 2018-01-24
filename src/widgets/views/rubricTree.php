<?php
/*
 * Show rubric tree.
 */

use yii\helpers\Html;
use sergmoro1\blog\components\WebSlug;
?>

<?php if(count($rubrics) > 0): // if any rubrics were defined ?>
    <p class='portlet-title'>
        <?= $title; ?>
    </p>
    <?php foreach($rubrics as $rubric): // show the tree of rubrics ?>
    <div class="rubric-tree">
        <?php 
            if($rubric->id > 1)
                echo Html::a($rubric->getPrettyName(), ['post/index', 'rubric' => WebSlug::getWebname($rubric->name)]) . 
                    ($rubric->post_count ? ' <span class="badge">' . $rubric->post_count . '</span>' : ''); 
            elseif($rubric->post_count > 0)
                echo Html::a($rubric->name, ['post/index', 'rubric' => 1]) . ' <span class="badge">' . $rubric->post_count . '</span>'; 
        ?>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
