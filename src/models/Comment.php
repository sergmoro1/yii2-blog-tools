<?php
namespace sergmoro1\blog\models;

use Yii;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use sergmoro1\blog\components\RuDate;

use common\models\Post;

class Comment extends ActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_comment':
	 * @var integer $id
	 * @var string $content
	 * @var integer $status
	 * @var integer $created
	 * @var string $author
	 * @var string $email
	 * @var string $url
	 * @var integer $parent_id
	 * @var integer $model
	 */
	const STATUS_PENDING = 1;
	const STATUS_APPROVED = 2;
	const STATUS_ARCHIVED = 3;

	public $agree;
	public $verifyCode;

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
			[['content', 'author'], 'required'],
			[['author', 'email'], 'string', 'max' => 128],
			['thread', 'string', 'max' => 32],
			['status', 'in', 'range'=>[self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_ARCHIVED]],
			[['parent_id', 'model'], 'integer'],
			['reply', 'boolean'],
			['email', 'email'],
			['content', 'string', 'max' => 512],
            ['agree', 'match', 'pattern' => '/^1$/', 'message' => \Yii::t('app', 'Please confirm that you agree to the processing of data sent by you.')],
			// verifyCode needs to be entered correctly
			['verifyCode', 'captcha', 'skipOnEmpty' => !Yii::$app->user->isGuest],
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
			'parent_id' => \Yii::t('app', 'Parent'),
			'model' => \Yii::t('app', 'Model'),
			'thread' => \Yii::t('app', 'Thread'),
			'status' => \Yii::t('app', 'Status'),
			'author' => \Yii::t('app', 'Name'),
			'location' => \Yii::t('app', 'Location'),
			'content' => \Yii::t('app', 'Content'),
			'agree' => \Yii::t('app', 'Consent to the processing of sent data'),
			'created_at' => \Yii::t('app', 'Created'),
			'verifyCode' => \Yii::t('app', 'Verification Code'),
		);
	}

	/**
	 * Approves a comment.
	 */
	public function approve()
	{
		static::save(['status' => Comment::STATUS_APPROVED]);
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

	public function getShortContent($max = 10)
	{
		$words = explode(' ', $this->content);
		$out = '';
		for($i=0; $i < count($words) && $i < $max; $i++)
			$out .= $words[$i] . ' ';
		return rtrim(trim($out), '.') . (count($words) < $max ? '' : '...');
	}

	/**
	 * @return string the hyperlink display for the current comment's author
	 */
	public function getAuthorLink()
	{
		if(!empty($this->url))
			return Html::a(Html::encode($this->author), $this->url);
		else
			return Html::encode($this->author);
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
			->where(['status' => self::STATUS_APPROVED])
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
			->where(['author_id' => $user_id])
			->all() as $post)
			
			$a[] = $post->id;
		return $a;
	}

	public function getDate()
	{
		$now = time();
		if(($hours = floor(($now - $this->created_at) / 3600)) <= 24)
			return $hours == 1 ? \Yii::t('blog', 'one hour ago') : $hours . \Yii::t('blog', 'hours ago');
		elseif($hpurs <= 48)
			return \Yii::t('blog', 'yesterday');
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
				$this->created_at = time();
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

	public function getPartContent($limit = 500)
	{
		$out = '';
		$words = explode(' ', $this->content);
		foreach($words as $word) {
			if(mb_strlen($out, 'UTF-8') <= $limit)
				$out .= $word . ' ';
			else {
				$out .= ' ...';
				break;
			}
		}
		return $out;
	}
}
