<?php
namespace sergmoro1\blog\components;

use yii\validators\Validator;
use sergmoro1\blog\Module;

/**
 * Checks unique except of the value of the attribute.
 * 
 * @author Sergey Morozov <sergey@vorst.ru>
 * 
 * In a model tree parts should be defined (slug attribute is taken as an example):
 * // 1
 * public $_slug
 * // 2
 * public function rules()
 * {
 *     return [
 *         ['_slug', 'safe'],
 * // 3
 * public function afterFind()
 * {
 *     parent::afterFind();
 *     $this->_slug = $this->slug;
 * 
 * And in a form hidden field should be added:
 * // 4
 *     <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
 *     <?= Html::activeHiddenInput($model, '_slug'); ?>
 */
class UniqueExceptItself extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $_attribute = '_' . $attribute;
        $value = $model->$attribute;
        $_value = $model->$_attribute;
        if (!($value === $_value)) {
            if($model->find()
                ->where([$attribute => $value])
                ->count() > 0
        ) {
                $model->addError($attribute, Module::t('core', '{attribute} "{value}" has already been taken.', [
                    'attribute' => $model->getAttributeLabel($attribute), 'value' => $_value,
                ]));
            }
        }
    }
}
