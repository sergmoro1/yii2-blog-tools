<?php

use yii\db\Schema;
use yii\db\Migration;

class m160206_162112_create_post extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'slug' => $this->string(128)->notNull()->unique(),
            'previous' => $this->integer()->notNull(),
            'title' => $this->string(128)->notNull(),
            'subtitle' => $this->string(128)->notNull(),
            'excerpt' => $this->text()->notNull(),
            'content' => $this->text()->notNull(),
            'resume' => $this->text()->notNull(),
            'tags' => $this->text(),
            'rubric' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('rubric', '{{%post}}', 'rubric');
        // $this->createIndex('FK_post_author', '{{%post}}', 'author_id');
        $this->addForeignKey ('FK_post_author', '{{%post}}', 'author_id', '{{%user}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%post}}');
    }
}
