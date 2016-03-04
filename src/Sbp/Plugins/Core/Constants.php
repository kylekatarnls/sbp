<?php

namespace Sbp\Plugins\Core;

class Constants
{
    /**
     * Get constant regex pattern.
     *
     * @param string constant name
     *
     * @return string regex pattern
     */
    private static function constantPattern($constant)
    {
        return '#(?<![a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff$]|::|->)__'.preg_quote($constant).'(?![a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff])#';
    }

    /***********************************/
    /* Replace quick access constants. */
    /***********************************/
    public static function quickAccessConstants($content, $caller)
    {
        $validSubst = call_user_func(array($caller, 'getValidStringSurrogates'));
        $validComments = call_user_func(array($caller, 'getValidComments'));
        $lastParsedFile = call_user_func(array($caller, 'getLastParsedFile'));

        $__file = is_null($lastParsedFile) ? null : realpath($lastParsedFile);
        if ($__file === false) {
            $__file = $lastParsedFile;
        }
        $__dir = is_null($__file) ? null : dirname($__file);
        $__file = call_user_func(array($caller, 'includeString'), $__file);
        $__dir = call_user_func(array($caller, 'includeString'), $__dir);
        $__server = array(
            'QUERY_STRING',
            'AUTH_USER',
            'AUTH_PW',
            'PATH_INFO',
            'REQUEST_METHOD',
            'USER_AGENT' => 'HTTP_USER_AGENT',
            'REFERER' => 'HTTP_REFERER',
            'HOST' => 'HTTP_HOST',
            'URI' => 'REQUEST_URI',
            'IP' => 'REMOTE_ADDR',
        );
        foreach ($__server as $key => $value) {
            if (is_int($key)) {
                $key = $value;
            }
            $content = preg_replace(
                static::constantPattern($key),
                '$_SERVER['.call_user_func(array($caller, 'includeString'), $value).']',
                $content
            );
        }

        /************************/
        /* Constantes spéciales */
        /************************/
        $content = preg_replace(
            static::constantPattern('FILE'),
            $__file,
            $content
        );

        $content = preg_replace(
            static::constantPattern('DIR'),
            $__dir,
            $content
        );

        foreach (array(
            'CLASS',
            'FUNCTION',
            'LINE',
            'METHOD',
            'NAMESPACE',
            'TRAIT',
        ) as $constant) {
            $content = preg_replace(
                static::constantPattern($constant),
                '__'.$constant.'__',
                $content
            );
        }

        return $content;
    }

    /********************/
    /* Ohter constants. */
    /********************/
    public static function otherConstants($content, $caller)
    {
        return array(
            '#'.constant($caller.'::START').'('.constant($caller.'::CONSTNAME').')\s*=#' => '$1const $2 =',
            '#\#('.constant($caller.'::CONSTNAME').')\s*=([^;]+);#' => 'define("$1",$2);',
            '#([\(;\s\.+/*=])~:('.constant($caller.'::CONSTNAME').')#' => '$1static::$2',
            '#([\(;\s\.+/*=]):('.constant($caller.'::CONSTNAME').')#' => '$1static::$2',
        );
    }
}
