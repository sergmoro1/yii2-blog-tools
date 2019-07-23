<?php
namespace sergmoro1\blog\migrations;

use yii\db\Schema;
use yii\db\Migration;

/**
 * @author Sergey Morozov <sergey@vorst.ru>
 */
class m160206_172112_create_post extends Migration
{
    private const TABLE_POST = '{{%post}}';
    private const TABLE_USER = '{{%user}}';
    
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(static::TABLE_POST, [
            'id'            => $this->primaryKey(),
            'user_id'       => $this->integer()->notNull(),
            'slug'          => $this->string(128)->notNull()->unique(),
            'previous_id'   => $this->integer()->defaultValue(0),
            'title'         => $this->string(128)->notNull(),
            'subtitle'      => $this->string(128),
            'excerpt'       => $this->text()->notNull(),
            'content'       => $this->text()->notNull(),
            'resume'        => $this->text(),
            'tags'          => $this->text(),
            'rubric_id'     => $this->integer()->defaultValue(1),
            'status'        => $this->integer()->defaultValue(1),

            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-rubric_id', static::TABLE_POST, 'rubric_id');
        $this->addForeignKey ('fk-post-user', static::TABLE_POST, 'user_id', static::TABLE_USER, 'id', 'CASCADE');

		$this->addCommentOnColumn(static::TABLE_POST, 'user_id',        'ID of the user who added the post');
		$this->addCommentOnColumn(static::TABLE_POST, 'slug',           'Symbolic ID');
		$this->addCommentOnColumn(static::TABLE_POST, 'previous_id',    'ID of the previous post in the chain of posts or null');
		$this->addCommentOnColumn(static::TABLE_POST, 'title',          'Title');
		$this->addCommentOnColumn(static::TABLE_POST, 'subtitle',       'Addition to the title');
		$this->addCommentOnColumn(static::TABLE_POST, 'excerpt',        'Short description');
		$this->addCommentOnColumn(static::TABLE_POST, 'content',        'Content');
		$this->addCommentOnColumn(static::TABLE_POST, 'resume',         'Final resume');
		$this->addCommentOnColumn(static::TABLE_POST, 'tags',           'Tags separated by commas');
		$this->addCommentOnColumn(static::TABLE_POST, 'rubric_id',      'Rubric ID');
		$this->addCommentOnColumn(static::TABLE_POST, 'status',         'Status');
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_POST);
    }
}
