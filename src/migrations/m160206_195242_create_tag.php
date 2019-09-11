<?php
namespace sergmoro1\blog\migrations;

use yii\db\Schema;
use yii\db\Migration;

class m160206_195242_create_tag extends Migration
{
    private const TABLE_TAG = '{{%tag}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(static::TABLE_TAG, [
            'id'        => $this->primaryKey(),
            'name'      => $this->string(128)->notNull(),
            'frequency' => $this->integer()->defaultValue(1),
            'show'      => $this->boolean()->notNull()->defaultValue(1),
        ], $tableOptions);

		$this->addCommentOnColumn(static::TABLE_TAG, 'name',        'Name');
		$this->addCommentOnColumn(static::TABLE_TAG, 'frequency',   'The number of occurrences in different posts');
		$this->addCommentOnColumn(static::TABLE_TAG, 'show',        'Show in frontend');
    }

    public function safeDown()
    {
        $this->dropTable('{{%tag}}');
    }
}
