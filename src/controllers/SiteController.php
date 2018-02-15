<?php
namespace sergmoro1\blog\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use sergmoro1\blog\Module;
use sergmoro1\blog\components\ParamsSyntaxChecker;

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

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Gear or params.
     *
     * @return mixed
     */

    public function actionGear()
    {
        $model = new \sergmoro1\blog\models\GearForm();
        $error = false;
        if ($model->load(\Yii::$app->request->post())) {
			$syntax = new ParamsSyntaxChecker();
			if($syntax->check($model->params)) {
				// new name for params file
				$f = 'params_'. date('Ymd_Hi', time()) .'.php';
				// copy current params to runtime folder with a new name of file
				copy(\Yii::getAlias('@frontend/runtime/params.php'), \Yii::getAlias('@frontend/runtime/' . $f));
				// save changes to runtime/params.php
				file_put_contents(\Yii::getAlias('@frontend/runtime/params.php'), $model->params);
				// copy just saved params to a frontend folder
				copy(\Yii::getAlias('@frontend/runtime/params.php'), \Yii::getAlias('@frontend/config/params.php'));
				return $this->goHome();
			} else
				$error = Module::t('core', 'Wrong syntax') . ($syntax->error_line ? ' in line: ' . $syntax->error_line : ': unpaired brackets') . '.';
        } else {
			// copy params to runtime folder and
            copy(\Yii::getAlias('@frontend/config/params.php'), \Yii::getAlias('@frontend/runtime/params.php'));
            // load it
            $model->params = file_get_contents(\Yii::getAlias('@frontend/runtime/params.php'));
        }
		return $this->render('gear', [
			'model' => $model,
			'error' => $error,
		]);
    }

    public function actionFrontend()
    {
        return $this->redirect($this->toFrontend());    
    }

    private function toFrontend()
    {
        return str_replace('back', 'front', (Url::base()
            ? Url::base()
            : \Yii::$app->request->hostInfo
        ));
    }
}
