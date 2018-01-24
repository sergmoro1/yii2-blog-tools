<?php
namespace backend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Gear form
 */
class GearForm extends Model
{
    public $params;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['params', 'string'],
            ['params', 'required'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'params' => 'Параметры',
        );
    }
}
