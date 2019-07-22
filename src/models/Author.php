<?php
namespace sergmoro1\blog\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

use sergmoro1\blog\Module;
use sergmoro1\uploader\behaviors\HaveFileBehavior;
use sergmoro1\uploader\models\OneFile;

/**
 * Posts authors.
 * 
 * @author Sergey Morozov <sergmoro1@ya.ru>
 */

class Author extends ActiveRecord
{
    /**
     * The followings are the available columns in table 'author':
     * @var integer $id
     * @var string  $name
     * @var integer $created_at
     * @var integer $updated_at
     */

    public $sizes = [
        'original'  => ['width' => 900, 'height' => 900, 'catalog' => 'original'],
        'main'      => ['width' => 300,  'height' => 300, 'catalog' => ''],
        'thumb'     => ['width' => 90,   'height' => 90,  'catalog' => 'thumb'],
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
        return [
            ['class' => TimestampBehavior::className()],
            [
                'class' => HaveFileBehavior::className(),
                'file_path' => '/files/author/',
            ],
         ];
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
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name' => Module::t('core', 'Name'),
        );
    }

    public function getFrequency() {
        return PostAuthor::find()->where(['author_id' => $this->id])->count();
    }
    
    public function getAll() {
        return ArrayHelper::map(Author::find()->all(), 'id', 'name'); 
    }
}
