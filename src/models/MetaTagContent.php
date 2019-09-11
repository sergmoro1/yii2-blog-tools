<?php

namespace sergmoro1\blog\models;

use sergmoro1\blog\models\MetaTag;
use notgosu\yii2\modules\metaTag\Module;
use Yii;
use yii\helpers\ArrayHelper;

class MetaTagContent extends \notgosu\yii2\modules\metaTag\models\MetaTagContent
{
    /**
     * @return string
     */
    public function getMetaTagContent()
    {
        $content = $this->content;
        $page = Yii::$app->request->get('page');

        if (($this->metaTag->name == MetaTag::META_TITLE_NAME || $this->metaTag->name == MetaTag::META_DESCRIPTION_NAME)
            && isset($page) && $page > 1
        ) {
            if (!empty($content)) {
                $content = Module::t('metaTag', 'Page') . ' ' . $page . '. ' . $content;
            }
        }
        return $content;
    }
}
