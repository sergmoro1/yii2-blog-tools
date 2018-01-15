<?php
namespace sergmoro1\blog\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use sergmoro1\blog\components\WebSlug;
use sergmoro1\blog\Module;

use sergmoro1\blog\models\Tag;

class TagCloud extends Widget
{
    public $title;

    public function init()
    {
        $this->title = mb_strtoupper(Module::t('core', 'Tags'), 'UTF-8');
        parent::init();
    }
    
    public function run()
    {
        if(\common\models\Post::getPublishedPostCount() > 0)
        {
            $tags = Tag::findTagWeights(Yii::$app->params['tagCloudCount']);
            
            echo '<p class="portlet-title">' . $this->title . '</p>';
            
            echo '<div class="tag-ref">';

            foreach($tags as $tag=>$weight)
            {
                $link = Html::a(Html::encode($tag), Url::to(['post/tag/' . WebSlug::getWebname($tag)]));
                $weight *= 1.5;
                echo Html::tag('span', $link, [
                    'class'=>'tag',
                    'style'=>"font-size:{$weight}pt",
                ])."\n";
            }
            
            echo '</div>';
        }
    }
}
