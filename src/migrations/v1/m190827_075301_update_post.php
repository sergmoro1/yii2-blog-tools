<?php

use yii\db\Migration;

/**
 * Class m190827_075301_update_post
 */
class m190827_075301_update_post extends Migration
{
    private const TABLE_POST = '{{%post}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn(static::TABLE_POST, 'previous', 'previous_id');
        $this->renameColumn(static::TABLE_POST, 'rubric',   'rubric_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn(static::TABLE_POST, 'previous_id', 'previous');
        $this->renameColumn(static::TABLE_POST, 'rubric_id',   'rubric');
    }
}
