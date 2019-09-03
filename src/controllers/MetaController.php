<?php

namespace sergmoro1\blog\controllers;

use yii\web\ForbiddenHttpException;

use notgosu\yii2\modules\metaTag\Module;
use notgosu\yii2\modules\metaTag\models\MetaTag;
use notgosu\yii2\modules\metaTag\controllers\TagController;

/**
 * TagController implements the CRUD actions for MetaTag model.
 */
class MetaController extends TagController
{
    /**
     * Lists all MetaTag models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!\Yii::$app->user->can('index', [], false))
            throw new ForbiddenHttpException(Module::t('metaTag', 'Access denied.'));
        return parent::actionIndex();
    }

    /**
     * Displays a single MetaTag model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (!\Yii::$app->user->can('view'))
            throw new ForbiddenHttpException(Module::t('metaTag', 'Access denied.'));
        return parent::actionView($id);
    }

    /**
     * Creates a new MetaTag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!\Yii::$app->user->can('create'))
            throw new ForbiddenHttpException(Module::t('metaTag', 'Access denied.'));

        $model = new MetaTag();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MetaTag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!\Yii::$app->user->can('update', ['model' => $model]))
            throw new ForbiddenHttpException(Module::t('metaTag', 'Access denied.'));
        
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MetaTag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('delete'))
            throw new ForbiddenHttpException(\Module::t('metaTag', 'Access denied.'));
        parent::actionDelete($id);
    }
}
