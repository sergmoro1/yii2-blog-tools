<?php
use yii\db\Migration;

class m170228_160540_create_event extends Migration
{
	const POST = '{{%post}}';
	const EVENT = '{{%event}}';
	
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::EVENT, [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'responsible' => $this->string(128),
            'begin' => $this->integer()->notNull(),
            'end' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('post_id', self::EVENT, 'post_id');
        $this->addForeignKey ('FK_event_post', self::EVENT, 'post_id', self::POST, 'id', 'CASCADE');

    }

    public function down()
    {
		$this->dropForeignKey('FK_event_post', self::EVENT);
		$this->dropIndex('post_id', self::EVENT);

        $this->dropTable(self::EVENT);
    }
}
