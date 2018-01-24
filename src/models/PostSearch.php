<?php
namespace sergmoro1\blog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\Post;
use common\models\User;

class PostSearch extends Post
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'rubric', 'status'], 'integer'],
            [['slug', 'title', 'tags'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Post::find();
        if(\Yii::$app->user->identity->group == User::GROUP_AUTHOR)
            $query->andFilterWhere(['author_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['postsPerPage'],
            ],
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC, 
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            if(isset($_GET['tag'])) {
                $this->tags = $_GET['tag'];
            } else
                return $dataProvider;
        }
        
        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['rubric' => $this->rubric])
            ->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }
}
