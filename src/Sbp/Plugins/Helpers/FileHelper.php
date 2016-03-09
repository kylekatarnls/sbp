<?php

namespace Sbp\Plugins\Helpers;

class FileHelper
{
    public static function getSbpSource($file)
    {
        if (preg_match('#/\*:(.+):\*/#U', file_get_contents($file), $match)) {
            return static::cleanPath($match[1]);
        }
    }

    public static function cleanPath($path)
    {
        $realPath = realpath($path);

        return $realPath === false ? $path : $realPath;
    }

    public static function matchingLetter($file)
    {
        if (fileowner($file) === getmyuid()) {
            return 'u';
        }
        if (filegroup($file) === getmygid()) {
            return 'g';
        }

        return 'o';
    }
}
