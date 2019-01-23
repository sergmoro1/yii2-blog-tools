<?php
/**
 * CanComment Behavior.
 *
 * For models for which users can leave comments.
 *    
 */
namespace sergmoro1\blog\models;

use \Yii;
use common\models\Comment;

trait CanComment {
    /**
     * Get LIMITed comments with OFFSET for selected model.
     * @return objects of comments
     */
    public function getComments($offset = 0)
    {
        // find all not equal threads for selected model
        $rows = Yii::$app->db
            ->createCommand('SELECT DISTINCT thread '.
                'FROM comment '.
                'WHERE parent_id=:parent_id AND model=:model AND status=:status '.
                'ORDER BY thread DESC '.
                'LIMIT '. Yii::$app->params['commentsPerPage'] .' OFFSET '. $offset)
            ->bindValues([
                ':parent_id' => $this->id,
                ':model' => self::COMMENT_FOR , 
                ':status' => Comment::STATUS_APPROVED,
            ])
            ->queryAll();
        // keep threads
        $a = []; foreach($rows as $row) $a[] = $row['thread'];
        // select all comments of kept threads
        return Comment::find()
            ->where('status=' . Comment::STATUS_APPROVED)
            ->andWhere(['in', 'thread', $a])
            ->orderBy('thread DESC, created_at ASC')
            ->all();
    }

    /**
     * Get count of comments for selected model.
     * @return integer
     */
    public function getCommentCount()
    {
        return count(Comment::findAll([
            'parent_id' => $this->id,
            'model' => self::COMMENT_FOR,
            'status' => Comment::STATUS_APPROVED
        ]));
    }

    /**
     * Adds a new comment for the model.
     * @param Comment the comment to be added
     * @return boolean whether the comment is saved successfully
     */
    public function addComment($comment)
    {
        if(Yii::$app->params['commentNeedApproval'])
            $comment->status = Comment::STATUS_PENDING;
        else
            $comment->status = Comment::STATUS_APPROVED;
        // set model and parent_id (parent model ID)
        $comment->model = self::COMMENT_FOR;
        $comment->parent_id = $this->id;
        if($comment->thread == '-')
            // set new thread
            $comment->thread = time() . uniqid();
        else
            // unmark previous comment
            Yii::$app->db->createCommand("UPDATE {{%comment}} SET last=0 WHERE thread='{$comment->thread}' AND last=1")->execute();
        // mark only last comment in a thread
        $comment->last = 1;
        if($comment->save()) {
            if(Yii::$app->params['notifyResponsibleAboutComment'])
                $comment->trigger(Comment::EVENT_JUST_ADDED);
            $comment->content = '';
            return true;
        } else
            return false;
    }
}

