<?php
namespace sergmoro1\blog\controllers;

class RssController extends \yii\web\Controller
{
    /**
     * @var int Cache duration, set null to disabled
     */
    protected $cacheDuration = 43200; // default 12 hour

    /**
     * @var string Cache filename
     */
    protected $cacheFilename = 'rss.xml';
    
    protected $needOwnViewPath = false;
    
    public function channel()
    {
        return [];
    }

    public function dataProvider()
    {
        return null;
    }

    public function actionIndex()
    {
        $cachePath = \Yii::$app->runtimePath.DIRECTORY_SEPARATOR.$this->cacheFilename;

        if ($this->channel())
        {
            if (empty($this->cacheDuration) || !is_file($cachePath) || filemtime($cachePath) < time() - $this->cacheDuration)
            {
                $this->layout = false;
                
                if(!$this->needOwnViewPath)
                    $this->setViewPath('@vendor/sergmoro1/yii2-blog-tools/src/views/rss');
                
                $xml = $this->render('index', [
                    'channel' => $this->channel(),
                    'dataProvider' => $this->dataProvider(),
                ]);
                file_put_contents($cachePath, $xml);
            } else {
                $xml = file_get_contents($cachePath);
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            \Yii::$app->getResponse()->getHeaders()->set('Content-Type', 'text/xml; charset=utf-8');
            return $xml;
        }
    }
}
