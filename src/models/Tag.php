<?php

namespace sergmoro1\blog\models;

use Yii;
use yii\db\ActiveRecord;
use sergmoro1\blog\Module;

/**
 * Tag dictionary.
 */
class Tag extends ActiveRecord
{
    /**
     * The followings are the available columns in table 'tag':
     * @var integer $id
     * @var string $name
     * @var integer $frequency
     */

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

    /**
     * @param string tags separated by commas
     * @return array $tags
     */
    public static function string2array($tags)
    {
        return preg_split('/\s*,\s*/',trim($tags),-1,PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param array $tags
     * @return string tags separated by commas
     */
    public static function array2string($tags)
    {
        return implode(', ',$tags);
    }

    /**
     * Update name of tag.
     * @param string $old
     * @param string $new
     */
    public static function updateName($old, $new)
    {
        // replace $old tag to empty if $old and $new tags exists in the same record
        $space = Yii::$app->db->createCommand("UPDATE {{%post}} SET tags = TRIM(REPLACE(tags, '{$old}', '')) WHERE tags LIKE '%{$old}%' AND tags LIKE '%{$new}%'")
            ->execute();
        // clear comma
        if ($space) {
            // middle
            Yii::$app->db->createCommand("UPDATE {{%post}} SET tags = REPLACE(tags, ', ,', ',') WHERE tags REGEXP ', ,'")
                ->execute();
            // begin, end
            Yii::$app->db->createCommand("UPDATE {{%post}} SET tags = IF(STRCMP(SUBSTR(tags, 1, 1), ',') = 0, SUBSTR(tags, 2), IF(STRCMP(SUBSTR(tags, -1, 1), ',') = 0, SUBSTR(tags, 1, LENGTH(tags) - 1), tags)) WHERE STRCMP(SUBSTR(tags, 1, 1), ',') = 0 OR STRCMP(SUBSTR(tags, -1, 1), ',') = 0")
                ->execute();
        }
        // replace an $old tag to a $new
        $updated = Yii::$app->db->createCommand("UPDATE {{%post}} SET tags = REPLACE(tags, '{$old}', '{$new}') WHERE tags LIKE '%{$old}%' AND tags NOT LIKE '%{$new}%'")
            ->execute();
        // delete $old tag
        $oldTag = Tag::findOne(['name' => $old]);
        $oldTag->delete();
        // replace frequency for a $new tag
        $model = Tag::findOne(['name' => $new]);
        if($model) {
            $model->frequency += $updated;
            $model->save(false);
        } else {
            $model = new Tag(['name' => $new, 'show' => $oldTag->show, 'frequency' => $updated]);
            $model->save();
        }
    }
    
    /**
     * Update tags frequencies.
     * 
     * @param string $oldTags
     * @param string $newTag
     */
    public function updateFrequency($oldTags, $newTags)
    {
        $oldTags = self::string2array($oldTags);
        $newTags = self::string2array($newTags);
        Tag::addTags(array_values(array_diff($newTags, $oldTags)));
        Tag::removeTags(array_values(array_diff($oldTags, $newTags)));
    }

    /**
     * Add tag if it's not exists yet or increment frequency.
     * 
     * @param array $tags
     */
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

    /**
     * Decrement frequencies and delete all tags with 0 frequency.
     * 
     * @param array $tags
     */
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
