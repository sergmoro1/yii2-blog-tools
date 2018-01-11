<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_122838_lookup_fill extends Migration
{
    public function up()
    {
		$this->insert('{{%lookup}}', ['name' => 'Draft|Черновик', 'code' => 1, 'type' => 'PostStatus', 'position' => 1]);
		$this->insert('{{%lookup}}', ['name' => 'Published|Опубликовано', 'code' => 2, 'type' => 'PostStatus', 'position' => 2]);
		$this->insert('{{%lookup}}', ['name' => 'Archive|Архив', 'code' => 3, 'type' => 'PostStatus', 'position' => 3]);

		$this->insert('{{%lookup}}', ['name' => 'Pending|Ожидание', 'code' => 1, 'type' => 'CommentStatus', 'position' => 1]);
		$this->insert('{{%lookup}}', ['name' => 'Approved|Подтверждено', 'code' => 2, 'type' => 'CommentStatus', 'position' => 2]);
		$this->insert('{{%lookup}}', ['name' => 'Archive|Архив', 'code' => 3, 'type' => 'CommentStatus', 'position' => 3]);

		$this->insert('{{%lookup}}', ['name' => 'Статьи', 'code' => 1, 'type' => 'CommentFor', 'position' => 1]);
    }

    public function down()
    {
		$this->delete('{{%lookup}}', 'type=:type', ['type' => 'PostStatus']);
		$this->delete('{{%lookup}}', 'type=:type', ['type' => 'CommentStatus']);
		$this->delete('{{%lookup}}', 'type=:type', ['type' => 'CommentFor']);
    }
}
