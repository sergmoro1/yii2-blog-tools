<?php
namespace sergmoro1\blog\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use sergmoro1\blog\Module;
use sergmoro1\blog\components\ParamsSyntaxChecker;
use yii\web\ForbiddenHttpException;
use yii\base\ErrorException;

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
        if (!\Yii::$app->user->can('gear'))
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

        $model = new \sergmoro1\blog\models\GearForm();
        $error = false;
        if ($model->load(\Yii::$app->request->post())) {
            $syntax = new ParamsSyntaxChecker();
            if($syntax->check($model->params)) {
                // new name for archive params file
                $f = 'params_'. date('Ymd_Hi', time()) .'.php';
                // if frontend/runtime/params do not exists then make it
                if(!is_dir(($dir = \Yii::getAlias('@frontend/runtime/params'))))
                    mkdir($dir);
                // save just edited params to the temporary file
                file_put_contents(\Yii::getAlias('@frontend/runtime/params/params.php'), $model->params);
                // verify array by loading it
                if(($params = include(\Yii::getAlias('@frontend/runtime/params/params.php'))) && is_array($params)) {
                    // copy current params to runtime folder with a new name of file
                    copy(\Yii::getAlias('@frontend/config/params.php'), \Yii::getAlias('@frontend/runtime/params/' . $f));
                    // save changes to runtime/params.php
                    if(file_put_contents(\Yii::getAlias('@frontend/config/params.php'), $model->params))
                        return $this->goHome();
                    else
                        $error = 'Params file can\'t be saved.';
                } else {
                    $error = 'Params loading error.';
                }
            } else
                $error = Module::t('core', 'Wrong syntax') . ($syntax->error['line'] 
                    ? ' ' . Module::t('core', 'near line') . ': ' . $syntax->error['line'] . 
                        ' ('. Module::t('core', 'after') . ' <code>' . $syntax->error['prev'] . '</code> ' . 
                        (isset($syntax->error['must']) && $syntax->error['must'] 
                            ? Module::t('core', 'should be') 
                            : Module::t('core', 'should not be')
                        ) . ' <code>' . $syntax->error['token'] . '</code>)'
                    : ': ' . Module::t('core', 'unpaired brackets')
                ) . '.';
        } else {
            // load params
            $model->params = file_get_contents(\Yii::getAlias('@frontend/config/params.php'));
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
        $frontend = isset(\Yii::$app->params['frontend']) 
            ? \Yii::$app->params['frontend']
            : 'frontend.';
            
        return str_replace($frontend, '' , str_replace('back', 'front', (Url::base()
            ? Url::base()
            : \Yii::$app->request->hostInfo
        )));
    }
}
