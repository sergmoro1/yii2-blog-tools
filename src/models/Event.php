<?php
namespace sergmoro1\blog\models;

use yii\db\ActiveRecord;
use yii\helpers\Html;

use sergmoro1\blog\Module;
use common\models\Post;

class Event extends ActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_rubric':
	 * @var integer $id
	 * @var integer $post_id
	 * @var integer $begin
	 * @var integer $end
	 * @var integer $created_at
	 * @var integer $updated_at
	 */
	
	private static $icons = ['*' => 'bell-o'];
	public $begin_date;
	public $end_date;
	
	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '{{%event}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['post_id', 'begin_date', 'responsible'], 'required'],
			['post_id', 'unique', 'message' => 'C данной статьей событие уже связано. Вы можете добавить новую статью или изменить существующее событие.'],
			['responsible', 'string', 'max' => 128],
			['begin_date', 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'begin'],
			['end_date', 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'end'],
			[['begin_date', 'end_date'], 'safe'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'post_id' => Module::t('core', 'Post'),
			'responsible' => Module::t('core', 'Responsible'),
			'begin' => Module::t('core', 'Begin'),
			'end' => Module::t('core', 'End'),
			'begin_date' => Module::t('core', 'Begin'),
			'end_date' => Module::t('core', 'End'),
		);
	}

	/**
	 * Retrieves the list of events based on the current search/filter conditions.
	 * @return ActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search($params)
	{
		$query = Event::find()
			->join('INNER JOIN', 'post', 'event.post_id = post.id')
			->where(['post.status' => Post::STATUS_PUBLISHED])
			->orderBy('begin DESC');

		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['itemsPerPage'],
			],
		]);
	}
	
	public function getPost()
	{
		return Post::findOne(['id' => $this->post_id]);
	}

	public function getEventsPosts()
	{
        $a = [];
		if($rubric = Rubric::item('events')) {
            foreach (Post::find()
                ->select(['title', 'id'])
                ->where(['status' => Post::STATUS_PUBLISHED, 'rubric' => $rubric->id])
                ->all() as $post) {
                $a[$post->id] = $post->getTitle();
            }
        }
        return $a;
	}

	public static function getTitle($title)
	{
		$a = explode('/', $title);
		return count($a) > 1 ? trim($a[1]) : $title;
	}

	public function getAll($limit = 3)
	{
		return Event::find()
    		->join('INNER JOIN', 'post', 'event.post_id = post.id')
			->where(['status' => Post::STATUS_PUBLISHED])
			->orderBy('begin DESC')
			->limit($limit)
			->all();
	}
}
