<?php
namespace sergmoro1\blog\migrations;

use yii\db\Schema;
use yii\db\Migration;

/**
 * @author Sergey Morozov <sergey@vorst.ru>
 */
class m160206_193635_create_rubric extends Migration
{
    private const TABLE_RUBRIC = '{{%rubric}}';
    
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(static::TABLE_RUBRIC, [
            'id'         => $this->primaryKey(),
            'lft'        => $this->integer()->notNull(),
            'rgt'        => $this->integer()->notNull(),
            'level'      => $this->integer()->notNull(),
            'name'       => $this->string(128)->notNull(),
            'slug'       => $this->string(128)->notNull(),
            'show'       => $this->boolean()->defaultValue(1),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-lft',   static::TABLE_RUBRIC, 'lft');
        $this->createIndex('idx-rgt',   static::TABLE_RUBRIC, 'rgt');
        $this->createIndex('idx-level', static::TABLE_RUBRIC, 'level');

		$this->addCommentOnColumn(static::TABLE_RUBRIC, 'lft',   'Left');
		$this->addCommentOnColumn(static::TABLE_RUBRIC, 'rgt',   'Right');
		$this->addCommentOnColumn(static::TABLE_RUBRIC, 'level', 'Level from the root');
		$this->addCommentOnColumn(static::TABLE_RUBRIC, 'name',  'Node name');
		$this->addCommentOnColumn(static::TABLE_RUBRIC, 'slug',  'Symbolic ID');
		$this->addCommentOnColumn(static::TABLE_RUBRIC, 'show',  'Show in frontend');

        $this->insert(static::TABLE_RUBRIC, [
            'id'         => 1,
            'lft'        => 1, 
            'rgt'        => 2, 
            'level'      => 1, 
            'name'       => 'Root', 
            'slug'       => 'root', 
            'show'       => 0, 
            'created_at' => time(), 
            'updated_at' => time()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_RUBRIC);
    }
}
