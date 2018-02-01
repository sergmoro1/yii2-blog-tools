<?php
/**
 * Post model.
 *
 * @var integer $id
 * @var string $slug
 * @var integer $author_id
 * @var integer $previous
 * @var string $title
 * @var string $subtitle
 * @var string $excerpt
 * @var string $content
 * @var string $resume
 * @var string $tags
 * @var integer $rubric
 * @var integer $status
 * @var integer $created_at
 * @var integer $updated_at
 */
namespace sergmoro1\blog\models;

use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;
use mrssoft\sitemap\SitemapInterface;
use sergmoro1\blog\components\RuDate;
use sergmoro1\blog\components\RuSlug;

use common\models\User;

class BasePost extends ActiveRecord implements SitemapInterface, Linkable
{
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;

    const COMMENT_FOR = 1;

    public $created_at_date;
    
    private $_oldTags;
    
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    public function behaviors()
    {
        return [
            'RuDate' => ['class' => RuDate::className()],
            'RuSlug' => ['class' => RuSlug::className()],
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
            [['previous', 'rubric'], 'integer'],
            ['previous', 'default', 'value' => 0],
            ['previous', 'already_selected', 'message' => \Yii::t('app', 'This article is already selected as the previous one.')],
            ['status', 'in', 'range'=>[self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_ARCHIVED]],
            [['slug', 'title', 'subtitle'], 'string', 'max'=>128],
            ['slug', 'unique'],
            ['slug', 'match', 'pattern' => '/^[0-9a-z-]+$/u', 'message' => \Yii::t('app', 'Slug may consists a-z, numbers and minus only.')],
			['tags', 'match', 'pattern' => '/^[а-яА-Я\w\s,]+$/u', 'message' => \Yii::t('app', 'Tags may consists alphabets, numbers and space only.')],
            ['tags', 'normalizeTags'],
            ['created_at_date', 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'created_at'],
            [['resume', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Normalizes the user-entered tags.
     */
    public function already_selected($attribute, $params)
    {
        if($this->$attribute && $this->find()->where($attribute . '=' . $this->$attribute . 
            ($this->id ? ' and id <> ' . $this->id : '')
        )->one())
            $this->addError($attribute, $params['message']);
    }

    /**
     * Normalizes the user-entered tags.
     */
    public function normalizeTags($attribute,$params)
    {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }


    public function getAuthor()
    {
        return User::findOne($this->author_id);
    }

    public function getComments($offset = 0)
    {
        $rows = \Yii::$app->db
            ->createCommand('SELECT DISTINCT thread '.
                'FROM comment '.
                'WHERE parent_id=:parent_id AND model=:model AND status=:status '.
                'ORDER BY thread DESC '.
                'LIMIT '. \Yii::$app->params['recordsPerPage'] .' OFFSET '. $offset)
            ->bindValues([
                ':parent_id' => $this->id,
                ':model' => self::COMMENT_FOR , 
                ':status' => Comment::STATUS_APPROVED,
            ])
            ->queryAll();
        $a = []; foreach($rows as $row) $a[] = $row['thread'];
        return Comment::find()
            ->where('parent_id=:parent_id AND model=:model AND status=:status', [
                ':parent_id' => $this->id,
                ':model' => self::COMMENT_FOR , 
                ':status' => Comment::STATUS_APPROVED,
            ])
            ->andWhere(['in', 'thread', $a])
            ->orderBy('thread DESC, created_at ASC')
            ->all();
    }

    public function getCommentCount()
    {
        return count(Comment::findAll(['parent_id' => $this->id, 'model' => self::COMMENT_FOR,  'status' => Comment::STATUS_APPROVED]));
    }
    
    public function getRubric()
    {
        return Rubric::findOne($this->rubric);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'author_id' => \Yii::t('app', 'Author'),
            'previous' => \Yii::t('app', 'Previous post'),
            'title' => \Yii::t('app', 'Title'),
            'subtitle' => \Yii::t('app', 'Sub Title'),
            'excerpt' => \Yii::t('app', 'Excerpt'),
            'content' => \Yii::t('app', 'Content'),
            'resume' => \Yii::t('app', 'Resume'),
            'tags' => \Yii::t('app', 'Tags'),
            'rubric' => \Yii::t('app', 'Rubric'),
            'status' => \Yii::t('app', 'Status'),
            'created_at' => \Yii::t('app', 'Created'),
            'created_at_date' => \Yii::t('app', 'Created'),
            'updated_at' => \Yii::t('app', 'Modified'),
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

    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * @return string the URL that shows the detail of the post
     */
    public function getUrl()
    {
        return Url::to(['post/view', 'slug' => $this->slug]);
    }

    /**
     * @return string clear title
     */
    public function getTitle()
    {
        return preg_replace('/[\[\]]/', '', $this->title);
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
    public function getTitleLink($friendlyUrl = true)
    {
        mb_internal_encoding("UTF-8");
        $title = $this->title;
        if(($left = mb_strpos($title, '[')) === false)
            $left = 0;
        if(($right = mb_strpos($title, ']')) === false)
            $right = mb_strlen($title);
        return mb_substr($title, 0, $left) . 
            Html::a(mb_substr($title, ($left ? $left + 1 : 0), $right - $left - ($left ? 1 : 0)), $this->getUrl($friendlyUrl)) . 
            mb_substr($title, $right + 1, mb_strlen($title) - $right - ($right ? 1 : 0));
    }

    /**
     * @return array a list of links that point to the post list filtered by every tag of this post
     */
    public function getTagLinks()
    {
        $links = [];
        foreach(Tag::string2array($this->tags) as $tag)
            $links[] = Html::a(Html::encode($tag), ['post/tag/' . str_replace(' ', '_', $tag)]);
        return $links;
    }

    /**
     * @param integer the maximum number of posts that should be returned
     * @return list of last posts that can be choiced as previous
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
     * @return title link list of previous posts
     */
    public function Previous()
    {
        $a = array();
        $previous = $this->previous;
        while($previous)
        {
            $post = $this->findOne($previous);
            $a[] = $post->getTitleLink();
            $previous = $post->previous;
        }
        if($a)
            return $a;
        elseif($post = static::find()
            ->where('status=' . self::STATUS_PUBLISHED . ' AND id<>' . $this->id)
            ->orderBy('created_at DESC')
            ->one()
        )
            return $post->getTitleLink();
        return '';
    }

    /**
     * @return title link list of next posts
     */
    public function Next()
    {
        $a = [];
        $next = $this->id;
        while($post = static::find()
            ->where('status=' . self::STATUS_PUBLISHED . ' and previous=' . $next)
            ->one()
        )
        {
            $a[] = $post->getTitleLink();
            $next = $post->id;
        }
        if($a)
            return $a;
        elseif($post = static::find()
            ->where('status=' . self::STATUS_PUBLISHED . ' AND id<>' . $this->id)
            ->orderBy('created_at ASC')
            ->one()
        )
            return $post->getTitleLink();
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
     * @param $limit - integer the maximum number of posts that should be returned
     * @param $slug - string rubric slug
     * @param $tag - string
     * @return array the most recently added posts
     */
    public function getRecentPosts($limit = 3, $slug = false, $tag = false)
    {
        $query = \common\models\Post::find()
            ->where(['status' => self::STATUS_PUBLISHED]);

        // posts from selected rubric and all it's sub rubric
        if($slug) {
            if($selectedRubric = Rubric::findOne(['slug' => $slug])) {
                $a = []; $a[] = $selectedRubric->id;
                foreach($selectedRubric->children()->all() as $child)
                    $a[] = $child->id;
                $query->andWhere(['in', 'rubric', $a]); // rubric IN ($a)
            }
        }
        // posts with tag
        if($tag && ($tag = str_replace('_', ' ', $tag)))
            $query->andWhere(['like', 'tags', $tag]); // tags LIKE "%$tag%"
        // posts from only recent posts rubrics
        if(!$slug && !$tag) {
            $a = [];
            foreach(\Yii::$app->params['recent-posts'] as $slug)
                $a[] = Rubric::findOne(['slug' => $slug])->id;
            $query->andWhere(['in', 'rubric', $a]); // rubric IN ($a)
        }
        return $query
            ->orderBy('created_at DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * Adds a new comment to this post.
     * This method will set status and post_id of the comment accordingly.
     * @param Comment the comment to be added
     * @return boolean whether the comment is saved successfully
     */
    public function addComment($comment)
    {
        if(\Yii::$app->params['commentNeedApproval'])
            $comment->status = Comment::STATUS_PENDING;
        else
            $comment->status = Comment::STATUS_APPROVED;
        $comment->model = self::COMMENT_FOR;
        $comment->parent_id = $this->id;
        if($comment->thread == '-')
            $comment->thread = time() . uniqid();
        else
            \Yii::$app->db->createCommand("UPDATE {{%comment}} SET reply=0 WHERE thread='{$comment->thread}'")->execute();
        $comment->reply = 1;
        return $comment->save();
    }

    /**
     * This is invoked when a record is populated with data from a find() call.
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
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
            $this->clearGarbage(['excerpt', 'content', 'resume']);
            if($this->isNewRecord)
            {
                $this->author_id = \Yii::$app->user->id;
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
    }

    /**
     * This is invoked after the record is deleted.
     */
    public function afterDelete()
    {
        parent::afterDelete();
        Comment::deleteAll(['model' => self::COMMENT_FOR, 'parent_id' => $this->id]);
        Tag::updateFrequency($this->tags, '');
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
        
