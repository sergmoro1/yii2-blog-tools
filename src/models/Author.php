<?php
/**
 * The followings are the available columns in table 'author':
 * @var integer $id
 * @var string $name
 */
namespace sergmoro1\blog\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use sergmoro1\blog\Module;
use sergmoro1\uploader\FilePath;
use sergmoro1\uploader\models\OneFile;


class Author extends ActiveRecord
{
	public $sizes = [
		'original' => ['width' => 1200, 'height' => 900, 'catalog' => 'original'],
		'main' => ['width' => 300, 'height' => 300, 'catalog' => '', 'crop' => true],
		'thumb' => ['width' => 90, 'height' => 90, 'catalog' => 'thumb', 'crop' => true],
	];

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%author}}';
    }

    /**
     * @inheritdoc
     */
	public function behaviors()
	{
        return array_merge(parent::behaviors(), [
			'FilePath' => [
				'class' => FilePath::className(),
				'file_path' => '/files/author/',
			],
        ]);
	}

	public function getFiles()
	{
        return OneFile::find()
            ->where('parent_id=:parent_id AND model=:model', [
				':parent_id' => $this->id,
				':model' => 'sergmoro1\blog\models\Author',
			])
			->orderBy('created_at')
            ->all();
	}

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 128],
            ['name', 'unique'],
            ['created_at', 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name' => Module::t('core', 'Name'),
            'created_at' => Module::t('core', 'Created at'),
        );
    }

	public function getFrequency() {
		return PostAuthor::find()->where(['author_id' => $this->id])->count();
	}
	
    public function getAll() {
		return ArrayHelper::map(Author::find()->all(), 'id', 'name'); 
	}
	
    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord)
            {
                $this->created_at = time();
            }
            return true;
        }
        else
            return false;
    }
}
