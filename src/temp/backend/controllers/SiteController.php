<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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
        $model = new \backend\models\GearForm();
		copy(\Yii::getAlias('@frontend/config/params.php'), \Yii::getAlias('@frontend/runtime/params.php'));
        $model->params = file_get_contents(\Yii::getAlias('@frontend/runtime/params.php'));
        if ($model->load(Yii::$app->request->post())) {
			copy(\Yii::getAlias('@frontend/config/params.php'), \Yii::getAlias('@frontend/runtime/params_'. date('dmy', time()) .'.php'));
			file_put_contents(\Yii::getAlias('@frontend/runtime/params.php'), $model->params);
			copy(\Yii::getAlias('@frontend/runtime/params.php'), \Yii::getAlias('@frontend/config/params.php'));
            return $this->goHome();
        }

        return $this->render('gear', [
            'model' => $model,
        ]);
    }
}
