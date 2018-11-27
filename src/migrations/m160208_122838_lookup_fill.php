<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_122838_lookup_fill extends Migration
{
    const LOOKUP = '{{%lookup}}';
    const PROPERTY = '{{%property}}';
    // Properties
    const POST_STATUS = 3;
    const COMMENT_STATUS = 4;
    const COMMENT_FOR = 5;
    
    public function up()
    {
        $this->insert(self::PROPERTY, ['id' =>  self::POST_STATUS, 'name' => 'PostStatus']);
        $this->insert(self::LOOKUP, ['name' => 'Черновик', 'code' => 1, 'property_id' => self::POST_STATUS, 'position' => 1]);
        $this->insert(self::LOOKUP, ['name' => 'Опубликовано', 'code' => 2, 'property_id' => self::POST_STATUS, 'position' => 2]);
        $this->insert(self::LOOKUP, ['name' => 'Архив', 'code' => 3, 'property_id' => self::POST_STATUS, 'position' => 3]);

        $this->insert(self::PROPERTY, ['id' => self::COMMENT_STATUS, 'name' => 'CommentStatus']);
        $this->insert(self::LOOKUP, ['name' => 'Ожидание', 'code' => 1, 'property_id' => self::COMMENT_STATUS, 'position' => 1]);
        $this->insert(self::LOOKUP, ['name' => 'Подтверждено', 'code' => 2, 'property_id' => self::COMMENT_STATUS, 'position' => 2]);
        $this->insert(self::LOOKUP, ['name' => 'Архив', 'code' => 3, 'property_id' => self::COMMENT_STATUS, 'position' => 3]);

        $this->insert(self::PROPERTY, ['id' => self::COMMENT_FOR, 'name' => 'CommentFor']);
        $this->insert(self::LOOKUP, ['name' => 'Статьи', 'code' => 1, 'property_id' => self::COMMENT_FOR, 'position' => 1]);
    }

    public function down()
    {
        $this->delete(self::LOOKUP, 'property_id=' . self::POST_STATUS);
        $this->delete(self::LOOKUP, 'property_id=' . self::COMMENT_STATUS);
        $this->delete(self::LOOKUP, 'code=1 AND prooerty_id=' . self::COMMENT_FOR);
        $this->delete(self::PROPERTY, 'id=' . self::POST_STATUS);
        $this->delete(self::PROPERTY, 'id=' . self::COMMENT_STATUS);
        $this->delete(self::PROPERTY, 'id=' . self::COMMENT_FOR);
    }
}
