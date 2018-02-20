<?php
namespace sergmoro1\blog\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class Menu extends Widget
{
    public $items;
    public $view = 'menu';
    public $ulClass = null;

    private function getUrl() {
        $mid = \Yii::$app->controller->module->id == \Yii::$app->id ? '' : \Yii::$app->controller->module->id;
        $cid = \Yii::$app->controller->id;
        $aid = \Yii::$app->controller->action->id;
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
        echo $this->render($this->view, [
            'items' => $this->items,
            'url' => $this->getUrl(),
            'ulClass' => $this->ulClass,
        ]);
    }
}

