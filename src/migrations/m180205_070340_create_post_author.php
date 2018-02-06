<?php

use yii\db\Migration;

/**
 * Class m180205_070340_create_postauthor
 */
class m180205_070340_create_post_author extends Migration
{
	public $table = '{{%post_author}}';
	
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('post_id', $this->table, 'post_id');
        $this->createIndex('author_id', $this->table, 'author_id');

        $this->addForeignKey ('FK_post_authot_post', '{{%post_author}}', 'post_id', '{{%post}}', 'id', 'CASCADE');
        $this->addForeignKey ('FK_post_authot_author', '{{%post_author}}', 'author_id', '{{%author}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->table);
    }
}
