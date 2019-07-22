<?php

use yii\db\Migration;

/**
 * Class m180205_070340_create_post_author
 */
class m180205_070340_create_post_author extends Migration
{
    private const TABLE_POST         = '{{%post}';
    private const TABLE_AUTHOR       = '{{%author}';
    private const TABLE_POST_AUTHOR  = '{{%post_author}}';
    
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(static::TABLE_POST_AUTHOR, [
            'id'        => $this->primaryKey(),
            'post_id'   => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-post_id', static::TABLE_POST_AUTHOR, 'post_id');
        $this->createIndex('idx-author_id', static::TABLE_POST_AUTHOR, 'author_id');

        $this->addForeignKey ('fk-post_authot-post', static::TABLE_POST_AUTHOR, 'post_id', static::TABLE_POST, 'id', 'CASCADE');
        $this->addForeignKey ('fk-post_authot-author', static::TABLE_POST_AUTHOR, 'author_id', static::TABLE_AUTHOR, 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_POST_AUTHOR);
    }
}
