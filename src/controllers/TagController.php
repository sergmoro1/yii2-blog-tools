<?php
namespace sergmoro1\blog\controllers;

use Yii;
use yii\web\ForbiddenHttpException;

use sergmoro1\blog\Module;
use sergmoro1\modal\controllers\ModalController;
use common\models\Post;
use sergmoro1\blog\models\Tag;
use sergmoro1\blog\models\TagSearch;

class TagController extends ModalController
{
    public $_tag; // old tag
    public function newModel() { return new Tag(); }
    public function newSearch() { return new TagSearch(); }

    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->can('update'))
            return $this->alert(Module::t('core', 'Access denied.'));

        $this->_tag = $model->name;
        
        if ($model->load(\Yii::$app->request->post())) {
            if ($this->_tag === $model->name)
                $model->save();
            else
                Tag::updateName($this->_tag, $model->name);
            return YII_DEBUG 
                ? $this->redirect(['index'])
                : $this->redirect(Yii::$app->request->referrer);
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

        $model = $this->findModel($id);

        // delete selected tag in all posts
        foreach(Post::find()->where(['like', 'tags', $model->name])->all() as $post) {
            $a = $model->string2array($post->tags);
            $i = array_search($model->name, $a);
            unset($a[$i]);
            $post->tags = $model->array2string($a);
            $post->save();
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Rewrite tags by posts.
     * @return mixed
     */
    public function actionRewrite()
    {
        $tags = [];
        $posts = Post::find()->select(['id', 'tags'])->asArray()->all();
        foreach($posts as $post) {
            $postTags = Tag::string2array($post['tags']);
            foreach($postTags as $postTag) {
                if (isset($tags[$postTag]))
                    $tags[$postTag] += 1;
                else
                    $tags[$postTag] = 1;
            }
        }
        $allTags = [];
        foreach($tags as $name => $frequency) {
            $show = false;
            if ($model = Tag::findOne(['name' => $name]))
                $show = $model->show;
            $allTags[] = ['name' => $name, 'show' => $show, 'frequency' => $frequency];
        }
        Tag::deleteAll();
        Yii::$app->db->createCommand()->batchInsert(Tag::tableName(), ['name', 'show', 'frequency'], $allTags)->execute();

        return $this->redirect(['index']);
    }
}
