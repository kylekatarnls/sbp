<?php

namespace Sbp\Plugins\Core;

class PhpOpenerTag
{
    /***************************/
    /* Complete PHP shrot-tags */
    /***************************/
    public static $openerTag = array(
        '#<\?(?!php)#',
        '<?php',
    );

    /***************************/
    /* Remove useless PHP tags */
    /***************************/
    public static $successiveTags = array(
        '#\?><\?php#',
        '',
    );

    /*****************************************/
    /* Mark the compiled file with a comment */
    /*****************************************/
    public static function addMarkerComment($content, $caller)
    {
        $lastParsedFile = call_user_func(array($caller, 'getLastParsedFile'));

        return
            '<?php '.
            constant($caller.'::COMMENT').
            (is_null($lastParsedFile) ? '' : '/*:'.$lastParsedFile.':*/').
            ' ?>'.
            $content;
    }
}
