<?php
/**
 * The followings are the available columns in table 'tag':
 * @var integer $id
 * @var string $name
 * @var integer $frequency
 */
namespace sergmoro1\blog\models;

use yii\db\ActiveRecord;
use sergmoro1\blog\Module;

class Tag extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%tag}}';
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
            ['frequency', 'integer'],
            ['name', 'string', 'max' => 128],
            ['show', 'integer'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name' => Module::t('core', 'Name'),
            'frequency' => Module::t('core', 'Frequency'),
            'show' => Module::t('core', 'Show'),
        );
    }

    /**
     * Returns tag names and their corresponding weights.
     * Only the tags with the top weights will be returned.
     * @param integer the maximum number of tags that should be returned
     * @return array weights indexed by tag names.
     */
    public static function findTagWeights($limit = 20)
    {
        $models = Tag::find()
            ->where('tag.show')
            ->orderBy('frequency DESC')
            ->limit($limit)
            ->all();

        $total = 0;
        foreach($models as $model)
            $total += $model->frequency;

        $tags = [];
        if($total>0)
        {
            foreach($models as $model)
                $tags[$model->name] = 8 + (int)(16 * $model->frequency / ($total + 10));
            ksort($tags);
        }
        return $tags;
    }

    /**
     * Suggests a list of existing tags matching the specified keyword.
     * @param string the keyword to be matched
     * @param integer maximum number of tags to be returned
     * @return array list of matching tag names
     */
    public function suggestTags($keyword, $limit = 20)
    {
        $tags = Tag::find()
            ->where('name LIKE :keyword', [
                ':keyword' => '%' . strtr($keyword, ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']) . '%'
            ])
            ->orderBy('frequency DESC, Name')
            ->limit($limit)
            ->all();
        $names = [];
        foreach($tags as $tag)
            $names[] = $tag->name;
        return $names;
    }

    public static function string2array($tags)
    {
        return preg_split('/\s*,\s*/',trim($tags),-1,PREG_SPLIT_NO_EMPTY);
    }

    public static function array2string($tags)
    {
        return implode(', ',$tags);
    }

    public function updateFrequency($oldTags, $newTags)
    {
        $oldTags = self::string2array($oldTags);
        $newTags = self::string2array($newTags);
        Tag::addTags(array_values(array_diff($newTags, $oldTags)));
        Tag::removeTags(array_values(array_diff($oldTags, $newTags)));
    }

    private function addTags($tags)
    {
        if($tag = Tag::find()
            ->where(['name' => $tags])
            ->one())
            $tag->updateCounters(['frequency' => 1]);
        
        foreach($tags as $name)
        {
            if(!Tag::find()->where(['name' => $name])->exists())
            {
                $tag = new Tag;
                $tag->name = $name;
                $tag->frequency = 1;
                $tag->save();
            }
        }
    }

    private function removeTags($tags)
    {
        if(empty($tags))
            return;
        if($tag = Tag::find()
            ->where(['name' => $tags])
            ->one())
            $tag->updateCounters(['frequency' => -1]);
        Tag::deleteAll('frequency<=0');
    }
}
