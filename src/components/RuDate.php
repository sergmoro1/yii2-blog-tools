<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 * This is the Behavior class for operations with date.
 * You should define behavior in ActiveRecord class.
 * 
 * public function behaviors() {
 *  return [
 *   'RuDate' => ['class' => RuDate::className()]
 *  ];
 * }
 */
namespace sergmoro1\blog\components;

use Yii;
use yii\base\Behavior;

class RuDate extends Behavior
{
    // Russian names of months
    private static $month = ['01'=>'Янв', '02'=>'Фев', '03'=>'Мар', '04'=>'Апр', '05'=>'Май', '06'=>'Июн', 
        '07'=>'Июл', '08'=>'Авг', '09'=>'Сен', '10'=>'Окт', '11'=>'Ноя', '12'=>'Дек'];

    public function getFullDate($attribute, $monthYear = false)
    {
        $d = $this->owner->$attribute;
        return Yii::$app->language == 'ru-RU' 
            ? ($monthYear
                ? self::$month[date('m', $d)] . ' ' . date('Y', $d)
                : date('d', $d) . ' ' . self::$month[date('m', $d)] . ' ' . date('y', $d)
              )
            : ($monthYear
                ? date('Y M', $d)
                : date('Y M d', $d)
              );
    }
}
