<?php

namespace sergmoro1\blog\models;

use yii\db\ActiveRecord;

/**
 * Table of links between posts and their authors.
 * 
 * @author Sergey Morozov <sergey@vorst.ru>
 */

class PostAuthor extends ActiveRecord
{
    /**
     * The followings are the available columns in table 'post_author':
     * @var integer $id
     * @var integer $post_id
     * @var integer $author_id
     */

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%post_author}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['post_id', 'author_id'], 'required'],
        ];
    }

    public function getAuthor()
    {
        return Author::findOne($this->author_id);
    }

    /**
     * Update authors list.
     * 
     * @param integer $post_id
     * @param array $old Authors ids
     * @param array $new Authors ids
     */
    public function updateAuthors($post_id, $old, $new)
    {
        if(!is_array($old)) $old = [];
        if(!is_array($new)) $new = [];
        // delete authors if some names have been deleted in a form
        foreach(array_diff($old, $new) as $i => $author_id) {
            if($link = PostAuthor::find()->where(['post_id' => $post_id, 'author_id' => $author_id])->one())
                $link->delete();
        }
        // add authors if somebody have been added in a form
        foreach(array_diff($new, $old) as $i => $author_id) {
            $link = new PostAuthor(['post_id' => $post_id, 'author_id' => $author_id]);
            $link->save();
        }
    }
}
