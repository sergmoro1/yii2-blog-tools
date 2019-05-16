<?php
namespace sergmoro1\blog\models;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use sergmoro1\blog\components\RuSlug;
use sergmoro1\blog\Module;

use common\models\Post;

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
    public $_position; // old position
    public $_slug; // old slug
    
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
            ['parent_node', 'required', 'on' => 'create'],
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max'=>255],
            ['slug', 'match', 'pattern' => '/^[0-9a-z-]+$/u', 'message' => Module::t('core', 'Slug may consists a-z, numbers and minus only.')],
            [['parent_node', 'position', 'show'], 'integer'],
            [['position', 'slug'], 'uniqueExceptItself'],
            ['show', 'default', 'value' => 1],
        ];
    }

    /**
     * Checks unique except of the value of the attribute.
     */
    public function uniqueExceptItself($attribute, $params)
    {
        $_attribute = '_' . $attribute;
        $value = $this->$attribute;
        $_value = $this->$_attribute;
        if($value <> $_value) {
            $found = false;
            if(Rubric::find()->select([$attribute])->where([$attribute => $value])->count() > 0) {
                $this->addError($attribute, Module::t('core', '{attribute} "{value}" has already been taken.', [
                    'attribute' => ucfirst($attribute), 'value' => $value,
                ]));
            }
        }
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
        return \Yii::$app->components['urlManager']['enablePrettyUrl']
            ? Url::to(['post/rubric/' . $this->slug])
            : Url::to(['post/index', 'rubric' => $this->slug]);
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
        $this->_position = $this->position;
        $this->_slug = $this->slug;
        $this->post_count = Post::find()
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
