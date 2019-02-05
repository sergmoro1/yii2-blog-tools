<?php
use yii\helpers\Url;
?><item>
    <title><![CDATA[<?=$model->getTitle()?>]]></title>
    <description><![CDATA[<?=$model->getExcerpt()?>]]></description>
    <link><?=Url::toRoute(['post/view', 'slug' => $model->slug], true)?></link>
    <guid isPermaLink="true"><?=Url::toRoute(['post/view', 'slug' => $model->slug], true)?></guid>
    <pubDate><?=date(DATE_RSS, $model->created_at)?></pubDate>
    <?php if($image = $model->getImage('')): ?>
    <media:thumbnail width="976" height="549" url="<?=\Yii::$app->request->hostInfo.$image?>"/>
    <?php endif; ?>
</item>
