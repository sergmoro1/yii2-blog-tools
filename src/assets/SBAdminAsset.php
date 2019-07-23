<?php
/**
 * @author Sergey Morozov <sergmoro1@ya.ru>
 */

namespace sergmoro1\blog\assets;

use yii\web\AssetBundle;

class SBAdminAsset extends AssetBundle
{
    public $sourcePath = '@vendor/sergmoro1/yii2-blog-tools/src/assets/src';
    public $css = [
        'css/sb-admin.css',
        'css/site.css',
    ];
    public $js = [
        'js/jquery.compatible.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'backend\assets\AppAsset',
    ];
}
