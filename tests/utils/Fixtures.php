<?php
namespace naspersclassifieds\realestate\openapi\tests\utils;


class Fixtures
{
    /**
     * @param string $name
     * @return string
     */
    public static function load($name)
    {
        return file_get_contents(dirname(__FILE__) . '/../fixtures/' . $name);
    }
}