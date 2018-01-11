<?php
namespace backend\modules\blog;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'sergmoro1\blog\controllers';

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
		\Yii::$app->i18n->translations['blog'] = [
			'class' => 'yii\i18n\PhpMessageSource',
			'sourceLanguage' => 'en-US',
			'basePath' => '@vendor/sergmoro1/blog-tools/src/messages',
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
		return \Yii::t($category, $message, $params, $language);
	}
}
