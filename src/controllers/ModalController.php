<?php

namespace sergmoro1\blog\controllers;

use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\web\ForbiddenHttpException;
use sergmoro1\blog\Module;

/**
 * Controller implements the CRUD actions by Modal way.
 */
class ModalController extends Controller
{
    /**
     * Lists all Domain models.
     * @return mixed
     */
    public function actionIndex()
    {
		if (!\Yii::$app->user->can('index', [], false))
			throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

		$searchModel = $this->newSearch();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
    }

    /**
     * Displays a single Domain model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if (!\Yii::$app->user->can('view'))
			return $this->alert(Module::t('core', 'Access denied.'));

		return $this->renderAjax('view', [
			'model' => $this->findModel($id),
		]);
    }


    public function actionValidate($scenario = false)
	{
		$model = $this->newModel();
		if($scenario)
			$model->scenario = $scenario;
		$request = \Yii::$app->getRequest();

		// Ajax validation including form open in a modal window
		if ($request->isAjax && $model->load($request->post())) {
			\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}

    public function fillin($model, $update = true)
    {
		return $model;
	}
	
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if (!\Yii::$app->user->can('create'))
			return $this->alert(Module::t('core', 'Access denied.'));

		$model = $this->newModel();
		$model = $this->fillin($model, false);
		
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->renderAjax('create', [
				'model' => $model,
			]);
		}
    }

    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);
		if (!\Yii::$app->user->can('update', ['model' => $model]))
			return $this->alert(Module::t('core', 'Access denied.'));

		$model = $this->fillin($model);
		
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(\Yii::$app->request->referrer);
		} else {
			return $this->renderAjax('update', [
				'model' => $model,
			]);
		}
    }

    /**
     * Deletes an existing Domain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if (!\Yii::$app->user->can('delete'))
			throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

		$this->findModel($id)->delete();

		return $this->redirect(['index']);
    }
    
    public function alert($message)
    {
        return '<div class="alert alert-danger" role="alert">'. $message .'</div>';
	}
}
