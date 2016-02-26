<?php

namespace Sbp\Plugins\Core;

class PHPUnit
{
    /*************************************/
    /* should key-word fo PHPUnit assert */
    /*************************************/
    public static $shouldNotNormalize = array(
        '#(?<=\s|^)should\s+not(?=\s)$#mU',
        'should not',
    );

    public static $shouldNot = array(
        '#^(\s*)(\S.*\s)?should\snot\s(.*[^;]);*\s*$#mU',
        '::__assertFalse',
    );

    public static $should = array(
        '#^(\s*)(\S.*\s)?should(?!\snot)\s(.*[^;]);*\s*$#mU',
        '::__assertTrue',
    );

    public static function __assertFalse($match, $caller)
    {
        list($all, $spaces, $before, $after) = $match;

        return $spaces.'>assertFalse('.
            $before.
            preg_replace('#
                (?<![a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff$])
                (?:be|return)
                (?![a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff])
            #x', 'is', $after).', '.
            call_user_func(array($caller, 'includeString'), $all).
        ');';
    }

    public static function __assertTrue($match, $caller)
    {
        list($all, $spaces, $before, $after) = $match;

        return $spaces.'>assertTrue('.
            $before.
            preg_replace('#
                (?<![a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff$])
                (?:be|return)
                (?![a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff])
            #x', 'is', $after).', '.
            call_user_func(array($caller, 'includeString'), $all).
        ');';
    }
}
