<?php 
/*
 * Top DropDown Menu.
 */

use yii\helpers\Html;
?>

<li><?= Html::a(\Yii::t('app', 'frontend'), ['/blog/site/frontend']) ?></li>
<?php if(\Yii::$app->user->isGuest): ?>
    <li><?= Html::a(\Yii::t('app', 'Login'), ['/user/site/login']) ?></li>
<?php else: ?>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?php if($image = \Yii::$app->user->identity->getImage('thumb')): ?>
                <img class="img-circle avatar" src="<?= $image ?>">
            <?php else: ?>
                <span class="glyphicon glyphicon-user"></span>
            <?php endif; ?>
            <?= \Yii::$app->user->identity->username ?>
            <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
            <?php foreach($items as $name => $item): ?>
                <?php if(isset($item['gear']) && $item['gear']) { if(!(\Yii::$app->user->can('gear'))) continue; } ?>
                <?php if(substr($name, 0, 7) == 'divider') { echo '<li class="divider"></li>'; continue; } ?>
                <li>
                    <?php $options = isset($item['options']) ? $item['options']: []; ?>
                    <?php $url = in_array($item['url'], array_keys($replace)) ? $replace[$item['url']] : [$item['url']]; ?>
                    <?= Html::a('<span class="'. $item['icon'] .'"></span> '. \Yii::t('app', $item['caption']), $url, $options); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </li>
<?php endif; ?>
