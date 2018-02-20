<?php
/**
 * Post model.
 *
 */

namespace common\models;

use sergmoro1\blog\models\BasePost;
use sergmoro1\uploader\FilePath;
use sergmoro1\uploader\models\OneFile;
use notgosu\yii2\modules\metaTag\components\MetaTagBehavior;

class Post extends BasePost
{
    public $sizes = [
        'original' => ['width' => 2400, 'height' => 1600, 'catalog' => 'original'],
        'main' => ['width' => 900, 'height' => 600, 'catalog' => ''],
        'medium' => ['width' => 360, 'height' => 240, 'catalog' => 'medium/'],
        'thumb' => ['width' => 120, 'height' => 80, 'catalog' => 'thumb/'],
    ];

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'FilePath' => [
                'class' => FilePath::className(),
                'file_path' => '/files/post/',
            ],
            'seo' => [
                'class' => MetaTagBehavior::className(),
                'languages' => ['ru'],
            ],
        ]);
    }

    public function getFiles()
    {
        return $this->hasMany(OneFile::className(), ['parent_id' => 'id'])
            ->where('model=:model', [':model' => 'common\models\Post'])
            ->orderBy('created_at');
    }
}
        
