<?php
/**
 * RubricController implements the CRUD actions for Rubric model.
 */
namespace sergmoro1\blog\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

use sergmoro1\blog\Module;
use common\models\Post;
use common\models\User;
use sergmoro1\blog\models\Rubric;

class RubricController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('index'))
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

        $query = Rubric::find()->where('id>1');
 
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'lft' => SORT_ASC, 
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionValidate()
    {
        $model = new Rubric();
        $request = Yii::$app->getRequest();

        // Ajax validation including form open in a modal window
        if ($request->isAjax && $model->load($request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Creates a new Rubric model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('create'))
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

        $model = new Rubric();
        $model->node_id = Rubric::ROOT;
        $model->type = Rubric::NODE_PARENT;
        
        if ($model->load(Yii::$app->request->post()) && $this->move($model)) {
            return YII_DEBUG 
                ? $this->redirect(['index'])
                : $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Rubric model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('update'))
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

        $model = $this->findModel($id);

        if($parent = $model->parents(1)->one())
        {
            $model->node_id = $parent->id;
            $model->type = Rubric::NODE_PARENT;
            
            if ($model->load(Yii::$app->request->post())) {
                if (($model->node_id != $parent->id)) {
                    $this->move($model);
                } else {
                    if(!$model->save()) {
                        Yii::$app->session->setFlash('danger', Module::t('core', 'Rubric can not be saved.'));
                    }
                }
                return YII_DEBUG 
                    ? $this->redirect(['index'])
                    : $this->redirect(Yii::$app->request->referrer);
            } else {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
        } else {
            Yii::$app->session->setFlash('warning', Module::t('core', 'Node has not parent.'));
            return $this->redirect(['index']);
        }
    }
    
    /**
     * Validate and save the model to the selected location in a rubric tree.
     * appendTo(), insertAfter() call validate() and save().
     * @param mixid $model
     * @return boolean
     */
    private function move($model) 
    {
        $node = $this->findModel($model->node_id);
        switch ($model->type) {
            case Rubric::NODE_PARENT:
                return $model->appendTo($node);
            case Rubric::NODE_NEIGHBOR:
                return $model->insertAfter($node);
            case Rubric::NODE_RECIPIENT:
                return $this->merge($model->id, $model->node_id);
        }
    }

    private function merge($deleted_id, $recipient_id)
    {
        $node = $this->findModel($deleted_id);
        // find all node childrens
        $childrens = $node->children()->all();
        $node->deleteWithChildren();
        // update field rubric_id to $recipient_id in all posts with rubric $deleted_id or it's childrens id
        $ids = $deleted_id;
        foreach($childrens as $node)
            $ids .= ',' . $node->id;
        return Post::updateAll(['rubric_id' => $recipient_id], 'rubric_id IN (' . $ids . ')');
    }
    
    /**
     * Deletes an existing Rubric model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('delete'))
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

        if($id == Rubric::ROOT)
            Yii::$app->session->setFlash('warning', Module::t('core', 'Node can not be deleted.'));
        else
            $this->merge($id, Rubric::ROOT);

        return $this->redirect(['index']);
    }

    /**
     * Finds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rubric::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('core', 'The requested model does not exist.'));
        }
    }
}
