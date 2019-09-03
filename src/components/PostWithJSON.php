<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 * Find Post with JSON definition in an excerpt field and decode it to an object that
 * can be used in a view or anywhere else.
 * 
 * Usage:
 * 
 * public function behaviors() {
 *  return [
 *   'PostWithJSON' => ['class' => 'frontend\components\PostWithJSON']
 *  ];
 * }
 */
namespace sergmoro1\blog\components;

use Yii;
use yii\base\Behavior;
use common\models\Post;
use yii\web\NotFoundHttpException;
use yii\base\InvalidValueException;

class PostWithJSON extends Behavior
{
    private static $_post;
    private static $_json = 0;
    
    public function getPostBySlug($slug)
    {
        if(self::$_post = Post::findOne(['slug' => $slug]))
            return self::$_post;
        else
            throw new NotFoundHttpException("The requested Post model ($slug) does not exist.");
    }

    public function getJSON()
    {
        if((self::$_json === 0) && !(self::$_json = json_decode(strip_tags(self::$_post->excerpt))))
            throw new InvalidValueException("Post (" . self::$_post->slug . ") has no correct JSON definition.");
            
        return self::$_json;
    }
}
