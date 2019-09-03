<?php

namespace sergmoro1\blog\tests\common\fixtures;

use yii\test\ActiveFixture;

/**
 * Post fixture
 */
class PostFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Post';
    public $depends = ['sergmoro1\blog\tests\common\fixtures\UserFixture'];
}
