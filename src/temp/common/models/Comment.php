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
}
        
