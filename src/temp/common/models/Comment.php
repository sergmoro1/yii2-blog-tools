<?php
/**
 * Comment model.
 *
 */

namespace common\models;

use sergmoro1\blog\models\BaseComment;

class Comment extends BaseComment
{
    /**
     * Get Url for the model. By default model is a Post.
     * The method must be overridden in the inherited class. 
     * @param model is the  post that this comment belongs to.
     * @return string the permalink URL for this comment
     */
    public function getUrl($model = null)
    {
        if($model === null)
            $model = $this->model == Post::COMMENT_FOR 
                ? $this->post
                : false;
        return $model ? $model->url . '#c' . $this->id : '';
    }

}
        
