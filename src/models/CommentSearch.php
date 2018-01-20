<?php
namespace sergmoro1\blog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use sergmoro1\user\models\User;

class CommentSearch extends Comment
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'status'], 'integer'],
            [['author', 'content'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Comment::find();
        if(\Yii::$app->user->identity->group == User::GROUP_AUTHOR)
        {
			// only comments for User's posts
			$userPosts= [];
			foreach(Post::find()
				->select(['id'])
				->where(['author_id' => Yii::$app->user->id])
				->all() as $post)
				$userPosts[] = $post->id;
			// post_id IN $userPosts
			$query->andFilterWhere(['post_id' => $userPosts]);
		}
 
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => \Yii::$app->params['recordsPerPage'],
			],
			'sort' => [
				'defaultOrder' => [
					'thread' => SORT_DESC, 
					'created_at' => SORT_ASC,
				]
			],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
			if(isset($_GET['author'])) {
				$this->author = $_GET['author'];
			} else
				return $dataProvider;
        }
        
        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
			->andFilterWhere(['like', 'author', $this->author])
			->andFilterWhere(['like', 'content', $this->content])
			->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }
}
