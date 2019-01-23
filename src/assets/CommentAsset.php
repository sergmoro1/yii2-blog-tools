<?php
/**
 * @author <sergmoro1@ya.ru>
 * @license GPL
 */

namespace sergmoro1\blog\assets;

use yii\web\AssetBundle;

class CommentAsset extends AssetBundle
{
    public $sourcePath = '@vendor/sergmoro1/yii2-blog-tools/src/assets';

    public $css = [
    ];
    public $js = [
        'js/comment.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
