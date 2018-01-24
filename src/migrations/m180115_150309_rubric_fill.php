<?php

use yii\db\Migration;

/**
 * Class m180115_150309_rubric_fill
 */
class m180115_150309_rubric_fill extends Migration
{
    public function up()
    {
        $this->insert('{{%rubric}}', [
            'lft' => 1, 'rgt' => 2, 'level' => 1, 
            'name' => 'Root', 'slug' => 'root', 
            'position' => 1, 'show' => 0, 
            'created_at' => time(), 'updated_at' => time()
        ]);

    }

    public function down()
    {
    }
}
