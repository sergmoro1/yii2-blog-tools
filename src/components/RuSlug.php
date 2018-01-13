<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 * use sergmoro1\blog\components\RuSlug;
 * 
 * public function behaviors() {
 *  return [
 *   'RuSlug' => ['class' => RuSlug::className()]
 *  ];
 * }
 */
namespace sergmoro1\blog\components;

use Yii;
use yii\base\Behavior;

class RuSlug extends Behavior
{
    public $slug = 'slug';
    public $attribute = 'title';
    public $delimiter = '-';
    
    public function translit() {
        $slug = $this->slug;
        $attribute = $this->attribute;
        if(!trim($this->owner->$slug))
        {
            $lowcase = mb_strtolower($this->owner->$attribute, 'utf-8');
            $cleared = str_replace(
                [
                    '?', '!', ',', ':', ';', '*', '(', ')', 
                    '{', '}', '%', '#', '№', '@', '$', '^', '-',
                    '+', '/', '\\', '=', '|', '"', '\'', '«', '»',
                ], '', $lowcase);  
            $this->owner->$slug = str_replace(
                [
                    'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к',  
                    'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х',  
                    'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я',
                ],
                [  
                    'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k',  
                    'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h',  
                    'j', 'i', 'e', $this->delimiter, 'zh', 'ts', 'ch', 'sh', 'shch', '', 'yu', 'ya',
                ], $cleared);
        }
    }
}
