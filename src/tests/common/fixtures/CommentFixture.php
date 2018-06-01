<?php

namespace sergmoro1\blog\tests\common\fixtures;

use yii\test\ActiveFixture;

/**
 * Comment fixture
 */
class CommentFixture extends ActiveFixture
{
    public $modelClass = 'sergmoro1\blog\models\Comment';
    public $depends = ['sergmoro1\blog\tests\common\fixtures\PostFixture'];
}
