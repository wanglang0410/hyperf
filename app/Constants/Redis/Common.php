<?php


namespace App\Constants\Redis;


trait Common
{
    public static function getName()
    {
        return 'cache:wl';
    }

    public static function createName($name, $prefix)
    {
        return implode(':', [self::getName(), $prefix, $name]);
    }

    public static function getListName($name = '')
    {
        return self::createName($name, 'list');
    }

    public static function getSetName($name = '')
    {
        return self::createName($name, 'set');
    }

    public static function getZSetName($name = '')
    {
        return self::createName($name, 'zSet');
    }

    public static function getHashName($name = '')
    {
        return self::createName($name, 'hash');
    }
}