<?php

namespace sergmoro1\blog\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use creocoder\nestedsets\NestedSetsBehavior;
use sergmoro1\rukit\behaviors\TransliteratorBehavior;
use sergmoro1\blog\Module;

use common\models\Post;

/**
 * Runric model class (Nested Set).
 *
 * @author Seregey Morozov <sergey@vorst.ru>
 *    
 */
class Rubric extends ActiveRecord
{
    /**
     * The followings are the available columns in table 'tbl_rubric':
     * @var integer $id
     * @var integer $lft
     * @var integer $rgt
     * @var integer $level
     * @var string  $name
     * @var string  $slug
     * @var integer $show
     */
    public $_slug;             // old slug

    public $post_count;        // posts count for the rubric
    
    public $node_id;           // ID of node related to curent
    public $type;              // related node type - parent, neighbor
    
    const ROOT           = 1;
    const NODE_PARENT    = 1;
    const NODE_NEIGHBOR  = 2;
    
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
            [
                'class' => TimestampBehavior::className(),
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'depthAttribute' => 'level',
            ],
            [
                'class' => TransliteratorBehavior::className(),
                'from' => 'name',
            ],
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['node_id', 'name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max'=>255],
            ['slug', 'match', 'pattern' => '/^[0-9a-z-]+$/u', 'message' => Module::t('core', 'Slug may consists a-z, numbers and minus only.')],
            ['slug', 'sergmoro1\blog\components\UniqueExceptItself'],
            [['node_id', 'show'], 'integer'],
            ['show', 'default', 'value' => 1],
            ['type', 'in', 'range' => [self::NODE_PARENT, self::NODE_NEIGHBOR]],
            ['_slug', 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name'              => Module::t('core', 'Name'),
            'slug'              => Module::t('core', 'Slug'),
            'node_id'           => Module::t('core', 'Node'),
            'type'              => Module::t('core', 'Type of node use'),
            'post_count'        => Module::t('core', 'Posts'),
            'show'              => Module::t('core', 'Show'),
        );
    }

    /**
     * Get node type list.
     * @return array
     */
    public static function getTypeList() {
        return [
            self::NODE_PARENT     => Module::t('core', 'parent'),
            self::NODE_NEIGHBOR   => Module::t('core', 'neighbor'),
        ];
    }

    public function getUrl()
    {
        return Yii::$app->components['urlManager']['enablePrettyUrl']
            ? Url::to(['post/rubric/' . $this->slug])
            : Url::to(['post/index', 'rubric' => $this->slug]);
    }

    /**
     * Array of rubric names with slugs.
     * @param string | null root title
     * @return array
     */
    public static function items($rootTitle = null)
    {
        if(!$rootTitle) 
            $rootTitle = Module::t('core', 'Root');
        $a = [];
        $a[1] = $rootTitle;
        foreach(self::find()
            ->where('id>1')
            ->orderBy('lft ASC')->all() as $node
        )
            $a[$node->id] = $node->getPrettyName(true);
        return $a;
    }
    
    /**
     * Get Rubric by slug.
     * @param string $slug
     * @return object | false
     */
    public static function item($slug)
    {
        return $slug ? Rubric::findOne(['slug' => $slug]) : false;
    }

    /**
     * Retrieves the list of rubrics based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider
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
    
    /**
     * Get rubric name with indentation.
     * @param boolean view of indentation - plain or wrapped. 
     * @return ActiveDataProvider the data provider.
     */
    public function getPrettyName($plain = false)
    {
        $indentation = str_repeat('-', ($this->level - 2) * 3);
        return $plain
            ? $indentation . ' ' . $this->name
            : '<span class="branch">' . $indentation . '</span>' . ' ' . $this->name;
    }

    /**
     * Sets internal variables and count posts in current rubric.
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_slug = $this->slug;
        $this->post_count = Post::find()
            ->where(['rubric_id' => $this->id, 'status' => Post::STATUS_PUBLISHED])
            ->count();
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    public function beforeSave($insert)
    {
        $this->translit();
        return parent::beforeSave($insert);
    }
}
