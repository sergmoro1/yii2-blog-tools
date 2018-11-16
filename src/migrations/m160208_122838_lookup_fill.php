<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_122838_lookup_fill extends Migration
{
    const TABLE = '{{%lookup}}';
    const PROPERTY = '{{%property}}';
    const PID = 3; // property ID
    public function up()
    {
        $i = self::PID;
        $this->insert(self::PROPERTY, ['id' =>  $i, 'name' => 'PostStatus']);
        $this->insert(self::TABLE, ['name' => 'Черновик', 'code' => 1, 'property_id' => $i, 'position' => 1]);
        $this->insert(self::TABLE, ['name' => 'Опубликовано', 'code' => 2, 'property_id' => $i, 'position' => 2]);
        $this->insert(self::TABLE, ['name' => 'Архив', 'code' => 3, 'property_id' => $i, 'position' => 3]);

        $i++;
        $this->insert(self::PROPERTY, ['id' => $i, 'name' => 'CommentStatus']);
        $this->insert(self::TABLE, ['name' => 'Ожидание', 'code' => 1, 'property_id' => $i, 'position' => 1]);
        $this->insert(self::TABLE, ['name' => 'Подтверждено', 'code' => 2, 'property_id' => $i, 'position' => 2]);
        $this->insert(self::TABLE, ['name' => 'Архив', 'code' => 3, 'property_id' => $i, 'position' => 3]);

        $i++;
        $this->insert(self::PROPERTY, ['id' => $i, 'name' => 'CommentFor']);
        $this->insert(self::TABLE, ['name' => 'Статьи', 'code' => 1, 'property_id' => $i, 'position' => 1]);
    }

    public function down()
    {
        $i = self::PID;
        $this->delete(self::TABLE, 'property_id=' . $i);
        $this->delete(self::TABLE, 'property_id=' . ($i + 1)));
        $this->delete(self::TABLE, 'code=1 AND prooerty_id=' . ($i + 2));
        $this->delete(self::PROPERTY, 'id=' . $i);
        $this->delete(self::PROPERTY, 'id=' . ($i + 1));
        $this->delete(self::PROPERTY, 'id=' . ($i + 2));
    }
}
