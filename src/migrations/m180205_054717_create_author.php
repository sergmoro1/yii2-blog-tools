<?php
namespace sergmoro1\blog\migrations;

use yii\db\Migration;

/**
 * Class m180205_054717_create_author
 */
class m180205_054717_create_author extends Migration
{
    private const TABLE_AUTHOR = '{{%author}}';
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(static::TABLE_AUTHOR, [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

		$this->addCommentOnColumn(static::TABLE_AUTHOR, 'name', 'Author name');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable(static::TABLE_AUTHOR);
    }
}
