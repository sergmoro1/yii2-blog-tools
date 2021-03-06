<?php
namespace sergmoro1\blog\models;

use sergmoro1\blog\Module;
use yii\base\Model;

/**
 * Gear form to edit frontend\config\params.php as an array
 * with syntax verification.
 * 
 * @author Sergey Morozov <sergey@vorst.ru>
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
            'params' => Module::t('core', 'Settings'),
        );
    }
}
