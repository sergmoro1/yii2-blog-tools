<?php
use sergmoro1\blog\Module;
?>

<div class='row'>

    <div class='col-xs-6 prev'>
        <?php if(is_array($prev)): ?>
            <h4><?= Module::t('core', 'Previous posts'); ?></h4>
            <?php $count_prev_links = count($prev); foreach($prev as $i => $link): ?>
                <div><?= ($count_prev_links - $i) . '. ' . $link; ?></div>
            <?php endforeach; ?>
        <?php elseif($link = $prev): ?>
            <h4><?= Module::t('core', 'Previous post'); ?></h4>
            <div><?= $link; ?></div>
        <?php endif; ?>
    </div>
        
    <div class='col-xs-6 next text-right'>
        <?php if(is_array($next)): ?>
            <h4><?= Module::t('core', 'Next posts'); ?></h4>
            <?php foreach($next as $i => $link): ?>
                <div><?= $link . ' .' . ((isset($count_prev_links) ? $count_prev_links : 0) + $i + 2); ?></div>
            <?php endforeach; ?>
        <?php elseif($link = $next): ?>
            <h4><?= Module::t('core', 'Next post'); ?></h4>
            <div><?= $link; ?></div>
        <?php endif; ?>
    </div>

</div>
