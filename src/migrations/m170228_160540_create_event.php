<?php
namespace sergmoro1\blog\migrations;

use yii\db\Migration;

class m170228_160540_create_event extends Migration
{
	private const TABLE_POST = '{{%post}}';
	private const TABLE_EVENT = '{{%event}}';
	
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(static::TABLE_EVENT, [
            'id'            => $this->primaryKey(),
            'post_id'       => $this->integer()->notNull(),
            'responsible'   => $this->string(128),
            'begin'         => $this->integer()->notNull(),
            'end'           => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-post_id', static::TABLE_EVENT, 'post_id');
        $this->addForeignKey ('fk-event-post', static::TABLE_EVENT, 'post_id', static::TABLE_POST, 'id', 'CASCADE');

		$this->addCommentOnColumn(static::TABLE_EVENT, 'post_id',       'Post that describes the event');
		$this->addCommentOnColumn(static::TABLE_EVENT, 'responsible',   'Responsible person');
		$this->addCommentOnColumn(static::TABLE_EVENT, 'begin',         'Begin date');
		$this->addCommentOnColumn(static::TABLE_EVENT, 'end',           'End date');
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_EVENT);
    }
}
