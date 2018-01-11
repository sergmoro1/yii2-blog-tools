<?php

use yii\db\Schema;
use yii\db\Migration;

class m160206_175933_create_comment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'model' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'author' => $this->string(128)->notNull(),
            'content' => $this->text(),
            'status' => $this->integer()->notNull(),
            'location' => $this->string(128)->notNull(),
            'thread' => $this->string(32)->notNull()->defaultValue(time() + rand(1000, 9999)),
            'reply' => $this->boolean()->notNull()->defaultValue(0),

            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('FK_comment_post', '{{%comment}}', 'post_id');
        $this->addForeignKey ('FK_comment_post', '{{%comment}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%comment}}');
    }
}
