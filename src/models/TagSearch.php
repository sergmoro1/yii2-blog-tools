<?php
namespace sergmoro1\blog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use sergmoro1\blog\models\Tag;

class TagSearch extends Tag
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            ['id', 'integer'],
            ['name', 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Tag::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => \Yii::$app->params['recordsPerPage'],
			],
			'sort' => [
				'defaultOrder' => [
					'name' => SORT_ASC,
				]
			],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
        }
        
        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
			->andFilterWhere(['name' => $this->name]);

        return $dataProvider;
    }
}
