<?php

use yii\db\Schema;
use yii\db\Migration;
use sergmoro1\blog\models\BaseComment as Comment;
use sergmoro1\user\models\BaseUser as User;

class m180514_154633_update_comment extends Migration
{
    public function up()
    {
        // add link to user
        $this->addColumn('{{%comment}}', 'user_id', $this->integer());

        // rename
        $this->renameColumn('{{%comment}}', 'reply', 'last');

        // save all commentators as a user
        foreach(Comment::find()->all() as $comment) {
            // if user has not registered yet
            if(!($user = User::findOne(['email' => $comment->email])))
            {
                // add new user
                $user = new User([
                    'name' => $comment->author, 
                    'email' => $comment->email, 
                    'group' => User::GROUP_COMMENTATOR,
                ]);
                $user->generateAuthKey();
                $user->setPassword(date('Ymd-Hs', time()));
                $user->save();
            }
            // fix comment for the user 
            $comment->user_id = $user->id;
            $comment->save();
        }
        
        // drop columns
        $this->dropColumn('{{%comment}}', 'author');
        $this->dropColumn('{{%comment}}', 'location');
        $this->dropColumn('{{%comment}}', 'email');
    }

    public function down()
    {
        // add new fields
        $this->addColumn('{{%comment}}', 'author', $this->string(128)->notNull());
        $this->addColumn('{{%comment}}', 'location', $this->string(128)->defaultValue('')->notNull());
        $this->addColumn('{{%comment}}', 'email', $this->string(128)->notNull());
        // rename
        $this->renameColumn('{{%comment}}', 'last', 'reply');

        // fiil in just added fields
        foreach(Comment::find()->all() as $comment) {
            if($user = User::findOne($comment->user_id))
                $comment->save(['author' => $user->name, 'email' => $user->email]);
        }
        
        // drop no needed user ID
        $this->dropColumn('{{%comment}}', 'user_id');
        // delete all commentators
        User::deleteAll(['group' => User::GROUP_COMMENTATOR]);
    }
}
