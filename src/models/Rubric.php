<?php
namespace sergmoro1\blog\models;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use sergmoro1\blog\components\RuSlug;
use sergmoro1\blog\Module;

class Rubric extends ActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_rubric':
	 * @var integer $id
	 * @var integer $lft, $rgt
	 * @var integer $level
	 * @var string $name
	 * @var string $slug
	 * @var integer $position
	 * @var integer $visible
	 */
	public $parent_node; // the parent node for just added
	public $post_count; // posts count in a rubric
	
	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '{{%rubric}}';
	}

	public function behaviors()
	{
		return [
			'tree' => [
				'class' => NestedSetsBehavior::className(),
				'depthAttribute' => 'level',
			],
            'RuSlug' => [
                'class' => RuSlug::className(),
                'attribute' => 'name',
            ],
		];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['parent_node', 'name', 'slug'], 'required'],
			[['name', 'slug'], 'string', 'max'=>255],
			['slug', 'unique'],
			['slug', 'match', 'pattern' => '/^[0-9a-z-]+$/u', 'message' => Module::t('core', 'Slug may consists a-z, numbers and minus only.')],
			[['position', 'show'], 'integer'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => Module::t('core', 'Name'),
			'slug' => Module::t('core', 'Slug'),
			'parent_node' => Module::t('core', 'Parent node'),
			'post_count' => Module::t('core', 'Posts'),
			'position' =>  Module::t('core', 'Position'),
			'visible' =>  Module::t('core', 'Visible'),
			'show' =>  Module::t('core', 'Show'),
		);
	}

	public function getUrl()
	{
		return Url::to(['post/rubric/' . $this->slug]);
	}

	public static function items($rootTitle = null)
	{
		if(!$rootTitle) 
			$rootTitle = Module::t('core', 'Root');
		$a = [];
		$a[1] = $rootTitle;
		foreach(Rubric::find()->where('id>1')->orderBy('lft ASC')->all() as $node)
			$a[$node->id] = $node->getPrettyName(true) . ' - ' . $node->slug;
		return $a;
	}
	
	public static function item($slug)
	{
		return $slug ? Rubric::findOne(['slug' => $slug]) : false;
	}

	/**
	 * Retrieves the list of posts based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search()
	{
		$query = Rubric::find()
			->where('id>1')
			->orderBy('lft ASC, position DESC');

		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['itemsPerPage'],
			],
		]);
	}
	
	public function getPrettyName($dropdown = false)
	{
		return $dropdown 
			? str_repeat('-', ($this->level - 2) * 3) . ' ' . $this->name
			: '<span class="branch">' . str_repeat('-', ($this->level - 2) * 3) . '</span> ' . $this->name;
	}

	public function afterFind()
	{
		parent::afterFind();
	    $this->post_count = \common\models\Post::find()
		    ->where(['rubric' => $this->id, 'status' => Post::STATUS_PUBLISHED])
			->count();
	}

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			$this->updated_at = time();
			$this->translit();
			if($this->isNewRecord)
			{
				$this->created_at = $this->updated_at;
			}
			return true;
		}
		else
			return false;
	}
}
