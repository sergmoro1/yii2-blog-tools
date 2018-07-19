<?php
/**
 * RubricController implements the CRUD actions for Rubric model.
 */
namespace sergmoro1\blog\controllers;

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
        if (!\Yii::$app->user->can('index'))
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

        $query = Rubric::find()->where('id>1');
 
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'position' => SORT_ASC, 
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionValidate($_position = null, $_slug = null)
    {
        $model = new Rubric();
        $model->_position = $_position;
        $model->_slug = $_slug;
        $request = \Yii::$app->getRequest();

        // Ajax validation including form open in a modal window
        if ($request->isAjax && $model->load($request->post())) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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
        if (!\Yii::$app->user->can('create'))
            return $this->alert(Module::t('core', 'Access denied.'));

        $model = new Rubric();
        $request = \Yii::$app->getRequest();
        $loaded = $model->load($request->post());
        
        // prependTo() call validate() and save()
        if ($loaded && $model->prependTo($this->findModel($model->parent_node))) {
            return $this->redirect(['index']);
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
        if (!\Yii::$app->user->can('update'))
            return $this->alert(Module::t('core', 'Access denied.'));

        $model = $this->findModel($id);

        // $parent_node is empty, so it must be set
        if($one = $model->parents(1)->one())
        {
            $model->parent_node = $one->id;
            // new code
            $loaded = $model->load(\Yii::$app->request->post());
            
            // The General case
            if ($loaded && $model->save()) {
                return YII_DEBUG 
                    ? $this->redirect(['index'])
                    : $this->redirect(\Yii::$app->request->referrer);
            } else {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
        } else {
            \Yii::$app->session->setFlash(
                'warning',
                \Yii::t('core', 'Node has not parent.')
            );
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing Rubric model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('delete'))
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

        if($id == 1)
            \Yii::$app->session->setFlash(
                'warning',
                \Yii::t('core', 'Node can not be deleted.')
            );
        else {
            $node = $this->findModel($id);
            // find all node childrens
            $childrens = $node->children()->all();
            $node->deleteWithChildren();
            // update deleted rubrics to 1 in all posts with rubrics are $id or $childrens ID
            $ids = $id;
            foreach($childrens as $node)
                $ids .= ',' . $node->id;
            Post::updateAll(['rubric' => 1], 'rubric IN (' . $ids . ')');
        }

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
