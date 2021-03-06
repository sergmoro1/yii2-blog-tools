<?php
namespace sergmoro1\blog\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class Menu extends Widget
{
    public $viewFile = 'menu';
    public $tag = 'span';
    public $items;
    public $ulClass = null;
    public $replace = []; // replace [url => url with params]
    public $markActive = true; // current choice mark as an active

    private function getUrl() {
        if(!$this->markActive)
            return '';
        $mid = Yii::$app->controller->module->id == Yii::$app->id ? '' : Yii::$app->controller->module->id;
        $cid = Yii::$app->controller->id;
        $aid = Yii::$app->controller->action->id;
        $id = isset($_GET['slug']) ? $_GET['slug'] : (isset($_GET['id']) ? $_GET['id'] : '');
        $rubric = isset($_GET['rubric']) ? $_GET['rubric'] : '';
        $tag = isset($_GET['tag']) ? $_GET['tag'] : '';
        return ($mid ? $mid. '/' : '') . 
            $cid . '/' . 
            ($aid == 'index' 
                ? ($rubric 
                    ? 'rubric/' . $rubric . ($tag 
                        ? '/tag/' . $tag 
                        : '') 
                    : 'index') 
                : ($aid == 'view' ? $id : $aid)
            );
    }
    
    public function run()
    {
        echo $this->render($this->viewFile, [
            'tag' => $this->tag,
            'items' => $this->items,
            'url' => $this->getUrl(),
            'ulClass' => $this->ulClass,
            'replace' => $this->replace,
        ]);
    }
}

