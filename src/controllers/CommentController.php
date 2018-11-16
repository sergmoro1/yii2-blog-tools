<?php

namespace sergmoro1\blog\controllers;

use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;

use sergmoro1\blog\Module;
use sergmoro1\modal\controllers\ModalController;
use common\models\User;
use sergmoro1\blog\models\Comment;
use sergmoro1\blog\models\CommentSearch;

/**
 * PostController implements the CRUD actions for Post model.
 */
class CommentController extends ModalController
{
    public function newModel() { return new Comment(); }
    public function newSearch() { return new CommentSearch(); }

    /**
     * Reply on a comment.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionReply($id)
    {
        $comment = $this->findModel($id);
        if (!\Yii::$app->user->can('replyStranger', ['comment' => $comment]))
            return $this->alert(\Yii::t('app', 'Access denied.'));
        
        // fill in a new Comment
        $model = $this->newModel();
        $model->model = $comment->model;
        $model->parent_id = $comment->parent_id;
        $model->thread = $comment->thread;
        $model->user_id = \Yii::$app->user->id; // comment of a current user
        $model->status = Comment::STATUS_APPROVED; // comment approved by default
        $model->last = 1; // only the last comment in the thread can be replied

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            // the comment to which we reply must be approved
            if ($comment->status == Comment::STATUS_PENDING) {
                $comment->status = Comment::STATUS_APPROVED;
                $comment->save(false);
            }
            return YII_DEBUG 
                ? $this->redirect(['index'])
                : $this->redirect(\Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('reply', [
                'model' => $model,
                'comment' => $comment,
            ]);
        }
    }

    /**
     * Deletes an existing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('delete'))
            throw new ForbiddenHttpException(Module::t('core', 'Access denied.'));

        $model = $this->findModel($id);
        $model->delete();
        // mark last comment in a thread
        \Yii::$app->db->createCommand("UPDATE {{%comment}} SET last=1 WHERE thread='{$model->thread}' ORDER BY created_at DESC LIMIT 1")->execute();

        return $this->redirect(['index']);
    }
}
