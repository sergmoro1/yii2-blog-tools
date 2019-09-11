<?php
/**
 * Comment model.
 *
 */

namespace common\models;

use sergmoro1\blog\models\BaseComment;

class Comment extends BaseComment
{
	protected $commentFor = [1 => 'post'];

    /**
     * Post getter. Post is a model by default for what the comment for. 
     * For other models the same getter should be defined in common/models/Comment.php.
     * 
     * @return mixed
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'parent_id']);
    }

    /**
     * Get parent model name by code. 
     * 
     * @return string
     */
    public static function parentModelName($model) {
        return self::$commentFor[$model];
    }

    /**
     * Get all comments for Post with slug "testimonial".
     * 
     * @param integer max limmits of comments.
     * @return objects of comments.
     */
    public static function getTestimonials($limit = 5)
    {
        if($post = Post::findOne(['slug' => 'testimonial']))
        {
            return self::find()
                ->where([
                    'model' => Post::COMMENT_FOR, 
                    'parent_id' => $post->id,
                ])
                ->orderBy('created_at DESC')
                ->limit($limit)
                ->all();
        } else
            return null;
    }
}
        
