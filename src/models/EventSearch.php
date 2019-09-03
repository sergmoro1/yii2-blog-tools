<?php
namespace sergmoro1\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EventSearch extends Event
{
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'post_id', 'begin', 'end'], 'integer'],
            [['begin', 'end'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Event::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['recordsPerPage'],
			],
			'sort' => [
				'defaultOrder' => [
					'begin' => SORT_DESC,
				]
			],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
        }
        
        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
			// ->andFilWhere('begin > :begin', [':begin' => $this->begin]);

        return $dataProvider;
    }
}
