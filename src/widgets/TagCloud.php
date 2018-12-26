<?php
namespace sergmoro1\blog\widgets;

use yii\helpers\Html;
use yii\base\Widget;
use sergmoro1\blog\Module;
use sergmoro1\blog\components\WebSlug;
use sergmoro1\blog\models\Tag;

use common\models\Post;

class TagCloud extends Widget
{
    public $title = 'Tags';
    public $useWeight = false;
    public $coeff = 1.5;
    public $linkClass = '';
    public $view = 'tagCloud';
    public $prefix = '';
    public $glue = '-';
    public $mb_case = MB_CASE_TITLE;

    private $items;
    
    public function init()
    {
        $this->title = Module::t('core', $this->title);
        if(Post::getPublishedPostCount() > 0 && ($tags = Tag::findTagWeights(\Yii::$app->params['tagCloudCount'])))
        {
            $this->items = [];
            
            foreach($tags as $tag=>$weight)
            {
                $weight *= $this->coeff;
                $a = $this->useWeight ? ['style' => "font-size: {$weight}pt"] : [];
                if($this->linkClass)
                    $a['class'] = $this->linkClass;
                $this->items[] = Html::a($this->prefix.
                    mb_convert_case(Html::encode($tag), $this->mb_case, 'UTF-8'), 
                    (\Yii::$app->components['urlManager']['enablePrettyUrl']
                        ? ['post/tag/' . WebSlug::getWebname($tag, $this->glue)] 
                        : ['post/index', 'tag' => WebSlug::getWebname($tag, $this->glue)]
                    ), $a
                );
            }
        }
        parent::init();
    }
    public function run()
    {
        return $this->render($this->view, [
            'title' => $this->title,
            'items' => $this->items,
        ]);
    }
}
