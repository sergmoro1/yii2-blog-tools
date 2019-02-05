<?php
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
?><?xml version="1.0" encoding="UTF-8"?><rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title><![CDATA[<?=$channel['title']?>]]></title>
        <description><![CDATA[<?=$channel['description']?>]]></description>
        <link><?=$channel['link']?></link>
        <image>
            <url><?=$channel['image']['url']?></url>
            <title><?=$channel['image']['title']?></title>
            <link><?=$channel['image']['link']?></link>
        </image>
        <generator>RSS for Node</generator>
        <lastBuildDate><?=date(DATE_RSS, time())?></lastBuildDate>
        <copyright><![CDATA[<?=\Yii::$app->name?>. Все права защищены ]]></copyright>
        <language><![CDATA[<?=\Yii::$app->language?>]]></language>
        <managingEditor><![CDATA[<?=\Yii::$app->params['email']['to']['editor']?>]]></managingEditor>
        <ttl><?=$channel['ttl']?></ttl>
        <?=ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_view',
            'layout' => "{items}",
            'options' => ['tag' => false],
            'itemOptions' => ['tag' => false],
        ]);?>
    </channel>
</rss>
