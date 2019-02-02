<?php
namespace sergmoro1\blog\interfaces;

interface RssInterface
{
    /**
     * @return string
     */
    public function getTitle();


    /**
     * @return string
     */
    public function getExcerpt();
}
