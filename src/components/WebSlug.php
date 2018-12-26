<?php
/**
 * @author - Sergey Morozov <sergmoro1@ya.ru>
 * @license - MIT
 * 
 */
namespace sergmoro1\blog\components;

class WebSlug
{
    public static function getWebname($name, $glue = '-') {
        return str_replace(' ', $glue, $name);
    }

    public static function getRealname($web, $glue = '-') {
        return str_replace($glue, ' ', $web);
    }
}
