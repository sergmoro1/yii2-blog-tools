<?php
namespace sergmoro1\blog;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'sergmoro1\blog\controllers';
    public $sourceLanguage = 'en-US';

	public function init()
	{
		parent::init();

		$this->registerTranslations();
	}

	/**
	 * Register translate messages for module
	 */
	public function registerTranslations()
	{
		Yii::$app->i18n->translations['sergmoro1/blog/*'] = [
			'class' => 'yii\i18n\PhpMessageSource',
			'sourceLanguage' => $this->sourceLanguage,
			'basePath' => '@vendor/sergmoro1/yii2-blog-tools/src/messages',
	        'fileMap' => [
                'sergmoro1/blog/core' => 'core.php',
            ],
		];
	}

	/**
	 * Translate shortcut
	 *
	 * @param $category
	 * @param $message
	 * @param array $params
	 * @param null $language
	 *
	 * @return string
	 */
	public static function t($category, $message, $params = [], $language = null)
	{
		return Yii::t('sergmoro1/blog/' . $category, $message, $params, $language);
	}
}
