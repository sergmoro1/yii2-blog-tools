<?php

use yii\db\Schema;
use yii\db\Migration;

class m160206_193635_create_rubric extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%rubric}}', [
            'id' => $this->primaryKey(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'level' => $this->integer()->notNull(),
            'name' => $this->string(128)->notNull(),
            'slug' => $this->string(128)->notNull(),
            'position' => $this->integer()->notNull(),
            'show' => $this->boolean()->defaultValue(1),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('lft', '{{%rubric}}', 'lft');
        $this->createIndex('rgt', '{{%rubric}}', 'rgt');
        $this->createIndex('level', '{{%rubric}}', 'level');
    }

    public function down()
    {
        $this->dropIndex('lft', '{{%rubric}}');
        $this->dropIndex('rgt', '{{%rubric}}');
        $this->dropIndex('level', '{{%rubric}}');

        $this->dropTable('{{%rubric}}');
    }
}
