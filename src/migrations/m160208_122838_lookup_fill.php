<?php
namespace sergmoro1\blog\migrations;

use yii\db\Schema;
use yii\db\Migration;

class m160208_122838_lookup_fill extends Migration
{
    private const LOOKUP    = '{{%lookup}}';
    private const PROPERTY  = '{{%property}}';
    // Properties
    const POST_STATUS       = 3;
    const COMMENT_STATUS    = 4;
    const COMMENT_FOR       = 5;
    
    public function safeUp()
    {
        $this->insert(static::PROPERTY, ['id' =>  self::POST_STATUS, 'name' => 'PostStatus']);
        $this->insert(static::LOOKUP, ['name' => 'Черновик', 'code' => 1, 'property_id' => self::POST_STATUS, 'position' => 1]);
        $this->insert(static::LOOKUP, ['name' => 'Опубликовано', 'code' => 2, 'property_id' => self::POST_STATUS, 'position' => 2]);
        $this->insert(static::LOOKUP, ['name' => 'Архив', 'code' => 3, 'property_id' => self::POST_STATUS, 'position' => 3]);

        $this->insert(static::PROPERTY, ['id' => self::COMMENT_FOR, 'name' => 'CommentFor']);
        $this->insert(static::LOOKUP, ['name' => 'Статьи', 'code' => 1, 'property_id' => self::COMMENT_FOR, 'position' => 1]);
    }

    public function safeDown()
    {
        $this->delete(static::LOOKUP, 'property_id=' . self::POST_STATUS);
        $this->delete(static::LOOKUP, 'code=1 AND property_id=' . self::COMMENT_FOR);
        $this->delete(static::PROPERTY, 'id=' . self::POST_STATUS);
        $this->delete(static::PROPERTY, 'id=' . self::COMMENT_FOR);
    }
}
