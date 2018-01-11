<?php
namespace sergmoro1\blog\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['image-upload', 'file-upload', 'get-files', 'get-images', 'delete-file'],
                'rules' => [
                    [
                        'actions' => ['image-upload', 'file-upload', 'get-files', 'get-images', 'delete-file'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
			'image-upload' => [
				'class' => 'vova07\imperavi\actions\UploadFileAction',
				'url' => ($this->toFrontend() . '/files/common'),
				'path' => '@frontend/web/files/common',
				'uploadOnlyImage' => true,
				'translit' => true,
				'unique' => false,
			],
			'file-upload' => [
				'class' => 'vova07\imperavi\actions\UploadFileAction',
				'url' => ($this->toFrontend() . '/files/common'),
				'path' => '@frontend/web/files/common',
				'uploadOnlyImage' => false,
				'translit' => true,
				'unique' => false,
			],
            'get-images' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => ($this->toFrontend() . '/files/common'),
                'path' => '@frontend/web/files/common',
            ],	
            'get-files' => [
                'class' => 'vova07\imperavi\actions\GetFilesAction',
                'url' => ($this->toFrontend() . '/files/common'),
                'path' => '@frontend/web/files/common',
            ],	
            'delete-file' => [
                'class' => 'vova07\imperavi\actions\DeleteFileAction',
                'url' => ($this->toFrontend() . '/files/common'),
                'path' => '@frontend/web/files/common',
            ],	
        ];
    }

    public function actionFrontend()
    {
		return $this->redirect($this->toFrontend());	
    }

	private function toFrontend()
	{
		return str_replace('back', 'front', (Url::base()
			? Url::base()
			: Yii::$app->request->hostInfo
		));
	}
}
