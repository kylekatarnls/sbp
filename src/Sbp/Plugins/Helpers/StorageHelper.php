<?php

namespace Sbp\Plugins\Helpers;

class StorageHelper
{
    protected static $storage = array();

    public static function init()
    {
        static::$storage = array();
    }

    protected static function keyExists($key)
    {
        return array_key_exists($key, static::$storage);
    }

    public static function all($key)
    {
        return static::keyExists($key)
            ? static::$storage[$key]
            : array();
    }

    public static function regex($key)
    {
        return '(?:'.implode('|', static::all($key)).')';
    }

    public static function add($key, $value)
    {
        if (!static::keyExists($key)) {
            static::$storage[$key] = array();
        }
        $id = count(static::$storage[$key]);
        static::$storage[$key][$id] = $value;

        return $id;
    }
}
