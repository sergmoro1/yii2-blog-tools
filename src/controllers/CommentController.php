<?php

namespace sergmoro1\blog\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\User;
use sergmoro1\blog\models\Comment;
use sergmoro1\blog\models\CommentSearch;

/**
 * PostController implements the CRUD actions for Post model.
 */
class CommentController extends ModalController
{
	public $modelName = 'Comment';
    public function newModel() { return new Comment(); }
    public function newSearch() { return new CommentSearch(); }

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
     * Reply on a comment.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionReply($id)
    {
		$comment = $this->findModel($id);
		if (!\Yii::$app->user->can('replyComment', ['comment' => $comment]))
			return $this->alert(\Yii::t('app', 'Access denied.'));

		$model = $this->newModel();

		$model->parent_id = $comment->parent_id;
		$model->model = $comment->model;
		$model->thread = $comment->thread;
		\Yii::$app->db->createCommand("UPDATE {{%comment}} SET reply=0 WHERE thread='{$comment->thread}'")->execute();
		$model->author = \Yii::$app->user->identity->name;
		$model->email = \Yii::$app->user->identity->email;
		$model->status = Comment::STATUS_APPROVED;
		$model->reply = 1;
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(Yii::$app->request->referrer);
		} else {
			return $this->renderAjax('reply', [
				'model' => $model,
				'comment' => $comment,
			]);
		}
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'The requested model does not exist.'));
        }
    }
}
