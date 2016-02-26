<?php

namespace Sbp\Plugins\Core;

class ReplaceStrings
{
    /*******************************/
    /* Escape the escape-character */
    /*******************************/
    public static function escapeEscapeCharacter($content, $caller)
    {
        $subst = constant($caller.'::SUBST');

        return array(
            $subst,
            $subst.$subst,
        );
    }

    /*************************************************************/
    /* Save the comments, quoted string and HTML out of PHP tags */
    /*************************************************************/
    public static function saveComments($content, $caller)
    {
        return array(
            '#'.constant($caller.'::COMMENTS').'|'.call_user_func(array($caller, 'stringRegex')).'|\?>.+<\?php#sU',
            $caller.'::replaceString',
        );
    }
}
