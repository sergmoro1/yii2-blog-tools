<?php
namespace sergmoro1\blog\models;

use yii\helpers\Html;
use yii\db\ActiveRecord;
use sergmoro1\rudate\RuDate;
use sergmoro1\blog\Module;

use common\models\Post;
use common\models\User;

class Comment extends ActiveRecord
{
    /**
     * The followings are the available columns in table 'tbl_comment':
     * @var integer $id
     * @var integer $model
     * @var integer $parent_id
     * @var integer $user_id
     * @var string $content
     * @var integer $status
     * @var string $thread
     * @var boolean $last
     * @var integer $created_at
     */

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_ARCHIVED = 3;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    public function behaviors()
    {
        return [
            'RuDate' => ['class' => RuDate::className()],
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
            [['model', 'parent_id', 'user_id', 'content'], 'required'],
            ['thread', 'string', 'max' => 32],
            ['status', 'in', 'range'=>[self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_ARCHIVED]],
            ['status', 'default', 'value' => self::STATUS_PENDING],
            [['model', 'parent_id', 'user_id'], 'integer'],
            ['last', 'boolean'],
            ['content', 'string', 'max' => 512],
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'parent_id']);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'model' => Module::t('core', 'Model'),
            'parent_id' => Module::t('core', 'Parent'),
            'user_id' => Module::t('core', 'Name'),
            'thread' => Module::t('core', 'Thread'),
            'status' => Module::t('core', 'Status'),
            'content' => Module::t('core', 'Content'),
            'created_at' => Module::t('core', 'Created'),
        );
    }

    /**
     * Approves a comment.
     */
    public function approve()
    {
        static::save(['status' => Comment::STATUS_APPROVED]);
    }
    
    public function canBeAnswered() {
		$countInThread = Comment::find()
		    ->where(['thread' => $this->thread, 'user_id' => \Yii::$app->user->id])
		    ->count();
		return !\Yii::$app->user->isGuest &&
		    $this->last && // this is a last comment in a thread
		    \Yii::$app->user->identity->group == User::GROUP_COMMENTATOR && // you are a commentator
		    $this->user_id != \Yii::$app->user->id && // last comment not yours
		    $countInThread > 0; // you begin this thread
            
	}
    
    /**
     * @param Post the post that this comment belongs to. If null, the method
     * will query for the post.
     * @return string the permalink URL for this comment
     */
    public function getUrl($model = null)
    {
        if($model === null)
            $model = $this->model == Post::COMMENT_FOR 
                ? $this->post
                : false;
        return $model ? $model->url . '#c' . $this->id : '';
    }

    public function getPartContent($limit = 500)
    {
        $out = '';
        $words = explode(' ', $this->content);
        mb_internal_encoding('UTF-8');
        foreach($words as $word) {
            if(mb_strlen($out) <= $limit)
                $out .= $word . ' ';
            else {
                $out .= ' ...';
                break;
            }
        }
        return $out;
    }

    /**
     * @return string the hyperlink display for the current comment's author
     */
    public function getAuthor()
    {
        return User::findOne($this->user_id);
    }

    /**
     * @return string the hyperlink display for the current comment's author
     */
    public function getAuthorLink()
    {
        if(!empty($this->url))
            return Html::a($this->author->name, $this->url);
        else
            return $this->author->name;
    }

    /**
     * @return integer the number of comments that are pending approval
     */
    public function getPendingCommentCount()
    {
        return Comment::find()
            ->where(['status' => self::STATUS_PENDING])
            ->count();
    }

    /**
     * @param integer the maximum number of comments that should be returned
     * @return array the most recently added comments
     */
    public function findRecentComments($limit = 5)
    {
        return Comment::find()
            ->innerJoin('user', 'comment.user_id = user.id')
            ->where(['comment.status' => self::STATUS_APPROVED, 'group' => User::GROUP_COMMENTATOR])
            ->orderBy('created_at DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @param integer $user_id
     * @return array of IDs all user's posts
     */
    public function getUserPosts($user_id)
    {
        $a = [];
        foreach(Post::find()
            ->select(['id'])
            ->where(['user_id' => $user_id])
            ->all() as $post)
            
            $a[] = $post->id;
        return $a;
    }

    public function getDate()
    {
        $now = time();
        if(($hours = floor(($now - $this->created_at) / 3600)) <= 24)
            return $hours == 1 ? Module::t('core', 'one hour ago') : $hours . Module::t('core', 'hours ago');
        elseif($hpurs <= 48)
            return Module::t('core', 'yesterday');
        else
            return $this->getFullDate('created_at');
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($insert)
            {
                // all comments in the thread can't be replied except the last, so change previous last to 0
                \Yii::$app->db->createCommand("UPDATE {{%comment}} SET last=0 WHERE thread='{$this->thread}' AND last=1")->execute();
                $this->created_at = time();
            }
            return true;
        }
        else
            return false;
    }
    
    public static function getTestimonials($limit = 5)
    {
        if($post = Post::findOne(['slug' => 'testimonial']))
        {
            return Comment::find()
                ->where([
                    'model' => Post::COMMENT_FOR, 
                    'parent_id' => $post->id,
                ])
                ->orderBy('created_at DESC')
                ->limit($limit)
                ->all();
        } else
            return null;
    }
}
