<?php

namespace sergmoro1\blog\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\Link;
use yii\web\Linkable;

use mrssoft\sitemap\SitemapInterface;
use sergmoro1\blog\Module;
use sergmoro1\rukit\behaviors\FullDateBehavior;
use sergmoro1\rukit\behaviors\TransliteratorBehavior;
use sergmoro1\blog\components\WebSlug;
use sergmoro1\feed\interfaces\RssInterface;

use common\models\User;
use common\models\Comment;

/**
 * BasePost model class.
 * 
 * @author Sergey Morozov <sergey@vorst.ru>
 */
class BasePost extends ActiveRecord implements SitemapInterface, Linkable, RssInterface
{
    /**
     * The followings are the available columns in table 'post':
     * @var integer $id
     * @var string  $slug
     * @var integer $user_id
     * @var integer $previous_id
     * @var string  $title
     * @var string  $subtitle
     * @var string  $excerpt
     * @var string  $content
     * @var string  $resume
     * @var string  $tags
     * @var integer $rubric_id
     * @var integer $status
     * @var integer $created_at
     * @var integer $updated_at
     */

    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;

    public $created_at_date;
    public $authors = [];
    
    private $_oldTags;
    private $_oldAuthors;
    
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::className()],
            ['class' => FullDateBehavior::className()],
            ['class' => TransliteratorBehavior::className()],
         ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['title', 'excerpt', 'content', 'status'], 'required'],
            [['previous_id', 'rubric_id'], 'integer'],
            ['previous_id', 'default', 'value' => 0],
            ['previous_id', 'already_selected', 'message' => Module::t('core', 'This post is already selected as the previous one.')],
            ['status', 'in', 'range' => self::getStatuses()],
            ['status', 'default', 'value' => 1],
            [['slug', 'title', 'subtitle'], 'string', 'max'=>128],
            ['slug', 'unique'],
            ['slug', 'match', 'pattern' => '/^[0-9a-z-]+$/u', 'message' => Module::t('core', 'Slug may consists a-z, numbers and minus only.')],
            ['tags', 'match', 'pattern' => '/^[а-яА-Я\w\s,]+$/u', 'message' => Module::t('core', 'Tags may consists alphabets, numbers and space only.')],
            ['tags', 'normalizeTags'],
            ['created_at_date', 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'created_at'],
            [['resume', 'created_at', 'updated_at', 'authors'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'slug'              => Module::t('core', 'Slug'),
            'user_id'           => Module::t('core', 'Moderator'),
            'previous_id'       => Module::t('core', 'Previous post'),
            'title'             => Module::t('core', 'Title'),
            'subtitle'          => Module::t('core', 'Sub Title'),
            'excerpt'           => Module::t('core', 'Excerpt'),
            'content'           => Module::t('core', 'Content'),
            'resume'            => Module::t('core', 'Resume'),
            'tags'              => Module::t('core', 'Tags'),
            'rubric_id'         => Module::t('core', 'Rubric'),
            'status'            => Module::t('core', 'Status'),
            'authors'           => Module::t('core', 'Authors'),
            'created_at'        => Module::t('core', 'Created at'),
            'created_at_date'   => Module::t('core', 'Created at'),
            'updated_at'        => Module::t('core', 'Modified at'),
        ];
    }

    /**
     * Get statuses.
     * @return array
     */
    public static function getStatuses() {
        return [
            self::STATUS_DRAFT,
            self::STATUS_PUBLISHED,
            self::STATUS_ARCHIVED, 
        ];
    }

    /**
     * Retrieves the list of posts based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the needed posts.
     */
    public function search($params)
    {
        $query = static::find()->where(
            'author_id=:author_id and status=:status', 
            [
                ':author_id' => $this->author_id,
                ':status' => $this->status,
            ]
        );

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::app()->params['itemsPerPage'],
            ],
            'sort' => [
                'defaultOrder'=>['status' => SORT_ASC, 'updated_at' => SORT_DESC]
            ],
        ]);
    }

    /**
     * Is the attribute value was selected yet?
     * @param string $attribute name
     * $param array validator $params
     */
    public function already_selected($attribute, $params)
    {
        if($this->$attribute && self::find()->where($attribute . '=' . $this->$attribute . 
            ($this->id ? ' and id <> ' . $this->id : '')
        )->one())
            $this->addError($attribute, $params['message']);
    }

    /**
     * @return string the URL that shows the detail of the post
     */
    public function getUrl()
    {
        return Url::to(['post/view', 'slug' => $this->slug]);
    }

    /**
     * Normalizes the user-entered tags.
     */
    public function normalizeTags($attribute,$params)
    {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }

    /**
     * @return \yii\db\ActiveRecord user who placed the post.
     */
    public function getUser()
    {
        return User::findOne($this->user_id);
    }

    /**
     * @return array of \yii\db\ActiveRecord the post's authors.
     */
    public function getAuthors()
    {
        return PostAuthor::find()->where(['post_id' => $this->id])->all();
    }

    /**
     * @return string the post's authors.
     */
    public function getListAuthors($glue = ', ')
    {
        $a = [];
        foreach($this->getAuthors() as $link)
            $a[] = $link->author->name;
        return implode($glue, $a);
    }

    /**
     * @return \yii\db\ActiveRecord post's rubric.
     */
    public function getRubric()
    {
        return Rubric::findOne($this->rubric_id);
    }

    /**
     * @return boolean is post published?
     */
    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * @return string clear title
     */
    public function getTitle()
    {
        return preg_replace('/[\[\]]/', '', $this->title);
    }

    /**
     * @return string clear subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @return clear excerpt
     */
    public function getExcerpt()
    {
        return str_replace(["\n", "\r"], '', strip_tags($this->excerpt));
    }

    /**
     * @return text clearing from editor garbage 
     * @param array attribute names
     */
    private function clearGarbage($attributes)
    {
        for($i = 0; $i < count($attributes); $i++) {
            $attribute = $attributes[$i];
            $this->$attribute =  preg_replace('/"=""/', '', $this->$attribute);
        }
    }

    /**
     * @return array - title first word and it's tail
     */
    public function getHeadTail()
    {
        $title = $this->title;
        if(($pos = strpos($title, ']')) === false) {
            if(($pos = strpos($title, '[')) === false)
                $pos = strpos($title, ' ');
        }
        return [
            'head' => preg_replace('/[\[\]]/', '', substr($title, 0, $pos)), 
            'tail' => preg_replace('/[\[\]]/', '', substr($title, $pos))
        ];
    }

    /**
     * @return string only part ot the title useed as a link
     */
    public function getTitleLink($options = [])
    {
        mb_internal_encoding("UTF-8");
        $title = $this->title;
        if(($left = mb_strpos($title, '[')) === false)
            $left = 0;
        if(($right = mb_strpos($title, ']')) === false)
            $right = mb_strlen($title);
        return mb_substr($title, 0, $left) . 
            Html::a(mb_substr($title, ($left ? $left + 1 : 0), $right - $left - ($left ? 1 : 0)), ['post/view', 'slug' => $this->slug], $options) . 
            mb_substr($title, $right + 1, mb_strlen($title) - $right - ($right ? 1 : 0));
    }

    /**
     * @return array a list of links that point to the post list filtered by every tag of this post
     */
    public function getTagLinks()
    {
        $links = [];
        foreach(Tag::string2array($this->tags) as $tag) {
            if(($model = Tag::findOne(['name' => $tag])) && $model->show)
                $links[] = Html::a(Html::encode($tag), ['post/tag/' . str_replace(' ', '_', $tag)]);
        }
        return $links;
    }

    /**
     * @param integer the maximum number of posts that should be returned
     * @return array of last posts that can be choiced as previous
     */
    public function CanBePrevious($limit = 50)
    {
        $posts = static::find()
            ->where('status=' . self::STATUS_PUBLISHED . ($this->id ? ' and id<>' . $this->id : ''))
            ->orderBy('created_at DESC')
            ->limit($limit)
            ->all();
        $a = [];
        foreach($posts as $post)
            $a[$post->id] = $post->getTitle();
        return $a;
    }

    /**
     * @param array link $options
     * @return title link list of previous posts
     */
    public function Previous($options = [])
    {
        $a = array();
        $previous = $this->previous_id;
        while($previous)
        {
            $post = $this->findOne($previous);
            $a[] = $post->getTitleLink($options);
            $previous = $post->previous_id;
        }
        if($a)
            return $a;
        elseif($post = static::find()
            ->where('status=' . self::STATUS_PUBLISHED . ' AND id<>' . $this->id)
            ->orderBy('created_at DESC')
            ->one()
        )
            return $post->getTitleLink($options);
        return '';
    }

    /**
     * @param array link $options
     * @return title link list of next posts
     */
    public function Next($options = [])
    {
        $a = [];
        $next = $this->id;
        while($post = static::find()
            ->where('status=' . self::STATUS_PUBLISHED . ' and previous_id=' . $next)
            ->one()
        )
        {
            $a[] = $post->getTitleLink($options);
            $next = $post->id;
        }
        if($a)
            return $a;
        elseif($post = static::find()
            ->where('status=' . self::STATUS_PUBLISHED . ' AND id<>' . $this->id)
            ->orderBy('created_at ASC')
            ->one()
        )
            return $post->getTitleLink($options);
        return '';
    }

    /**
     * @return integer the number of posts that are published
     */
    public function getPublishedPostCount()
    {
        return BasePost::find()
            ->where(['status' => self::STATUS_PUBLISHED])
            ->count();
    }

    /**
     * Get recent posts.
     * 
     * @param integer $limit - integer the maximum number of posts that should be returned
     * @param string $rubric slug
     * @param string $tag
     * @return array the most recently added posts
     */
    public function getRecentPosts($limit = 3, $rubric = false, $tag = false)
    {
        $query = \common\models\Post::find()
            ->where(['status' => self::STATUS_PUBLISHED]);

        // posts from selected rubric and all it's sub rubric
        if($rubric) {
            if($selectedRubric = Rubric::findOne(['slug' => $rubric])) {
                $a = []; $a[] = $selectedRubric->id;
                foreach($selectedRubric->children()->all() as $child)
                    $a[] = $child->id;
                $query->andWhere(['in', 'rubric_id', $a]); // rubric_id IN ($a)
            }
        }
        // posts with tag
        if($tag && ($tag = WebSlug::getRealname($tag)))
            $query->andWhere(['like', 'tags', $tag]); // tags LIKE "%$tag%"
        // posts from only recent posts rubrics
        if(!$rubric && !$tag && Yii::$app->params['recent-posts']) {
            $a = [];
            foreach(Yii::$app->params['recent-posts'] as $slug)
                $a[] = Rubric::findOne(['slug' => $slug])->id;
            $query->andWhere(['in', 'rubric_id', $a]); // rubric_id IN ($a)
        }
        return $query
            ->orderBy('created_at DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * This is invoked when a record is populated with data from a find() call.
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
        $this->_oldAuthors = ArrayHelper::getColumn(PostAuthor::find()->where(['post_id' => $this->id])->all(), 'author_id');
        $this->authors = $this->_oldAuthors;
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            $this->translit();
            $this->clearGarbage(['excerpt', 'content', 'resume']);
            if($this->isNewRecord)
            {
                $this->user_id = Yii::$app->user->id;
            }
            return true;
        }
        else
            return false;
    }

    /**
     * This is invoked after the record is saved.
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Tag::updateFrequency($this->_oldTags, $this->tags);
        PostAuthor::updateAuthors($this->id, $this->_oldAuthors, $this->authors);
    }

    /**
     * This is invoked after the record is deleted.
     */
    public function afterDelete()
    {
        parent::afterDelete();
        Event::deleteAll(['post_id' => $this->id]);
        Tag::updateFrequency($this->tags, '');
        PostAuthor::updateAuthors($this->id, $this->_oldAuthors, []);
    }

    /**
     * @return \yii\db\ActiveQuery
     */        
    public static function sitemap()
    {
        return self::find()->where('status=' . self::STATUS_PUBLISHED);
    }

    /**
     * @return string
     */
    public function getSitemapUrl()
    {
        return Url::toRoute(['post/' . $this->slug, 'title' => $this->getTitle(false, 'ru')], true);
    }    

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['post/view', 'id' => $this->id], true),
        ];
    }
}
        
