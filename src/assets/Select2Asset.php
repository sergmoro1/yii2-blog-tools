<?php

namespace sergmoro1\blog\assets;

class Select2Asset extends \hiqdev\yii2\assets\select2\Select2Asset
{
    public $sourcePath = '@vendor/bower-asset/select2/dist';

    public $js = [
        'js/select2.full.min.js',
    ];

    public $css = [
        'css/select2.min.css',
    ];

}
