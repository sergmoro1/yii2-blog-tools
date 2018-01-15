<?php

namespace sergmoro1\blog\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use sergmoro1\blog\Module;

use common\models\Post;
use sergmoro1\blog\models\PostSearch;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
	private $_model;

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

		$searchModel = new PostSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->get());

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @param string $slug
     * @return mixed
     */
    public function actionView($id = 0, $slug= '')
    {
		$model = $this->findModel($id, $slug);
		if (\Yii::$app->user->can('viewPost', ['post' => $model])) {
			return $this->render('view', [
				'model' => $model,
			]);
		} else
			throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));
    }

	public function actionMore($slug, $offset)
	{
		if(\Yii::$app->getRequest()->isAjax) {
			$model = $this->loadModel(0, $slug);
			if($comments = $model->getComments($offset))
				return $this->renderAjax('_comments', [
					'post' => $model,
					'comments' => $comments,
				]);
			else
				return 'No more.';
		} else
			throw new ForbiddenHttpException(Module::t('core', 'Only ajax request suitable.'));
	}

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

		if (\Yii::$app->user->can('createPost')) {
			$model = new Post();
			$model->created_at_date = date('d.m.Y', time());

			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
		} else
			throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);
		if (\Yii::$app->user->can('update', ['post' => $model])) {
			$model->created_at_date = date('d.m.Y', $model->created_at);
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return $this->render('update', [
					'model' => $model,
				]);
			}
		} else
			throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if (\Yii::$app->user->can('delete')) {
			$model = $this->loadModel($id);
			foreach($model->files as $file)
				$file->delete();
			\Yii::$app->db->createCommand("DELETE FROM {{%comment}} WHERE model=". Post::COMMENT_FOR ." AND parent_id='{$id}'")->execute();

			$model->delete();

			return $this->redirect(['index']);
		} else
			throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $slug
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id, $slug = '')
    {
		if($this->_model === null) 
		{
			if(($this->_model = $id ? Post::findOne($id) : Post::findOne(['slug' => $slug])) !== null) 
			{
				return $this->_model;
			} else {
				throw new NotFoundHttpException(Module::t('core', 'The requested model does not exist.'));
			}
		}
	}
}
