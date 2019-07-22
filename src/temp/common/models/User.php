<?php
/**
 * User model.
 *
 */

namespace common\models;

use sergmoro1\user\models\BaseUser;
use sergmoro1\uploader\behaviours\HaveFileBehavior;
use sergmoro1\uploader\models\OneFile;

class User extends BaseUser
{
    public $sizes = [
        'original' => ['width' => 1600, 'height' => 900, 'catalog' => 'original'],
        'main' =>     ['width' => 400,  'height' => 400, 'catalog' => ''],
        'thumb' =>    ['width' => 90,   'height' => 90,  'catalog' => 'thumb'],
    ];

    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => HaveFileBehavior::className(),
                'file_path' => '/files/user/',
            ],
        ]);
    }

    public function getFiles()
    {
        return OneFile::find()
            ->where('parent_id=:parent_id AND model=:model', [
                ':parent_id' => $this->id,
                ':model' => 'common\models\User',
            ])
            ->orderBy('created_at')
            ->all();
    }
}
