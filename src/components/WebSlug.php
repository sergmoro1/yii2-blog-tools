<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 */
namespace sergmoro1\blog\components;

class WebSlug
{
    static $glue = '-';

    public static function getWebname($name) {
        return str_replace(' ', self::$glue, $name);
    }

    public static function getRealname($web) {
        return str_replace(self::$glue, ' ', $web);
    }
}
