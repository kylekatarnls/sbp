<?php

namespace Sbp\Plugins\Core;

class CompileStrings
{
    public static $ignoreCarriageReturn = array("\r", ' ');

    public static function replaceStrings($content, $caller)
    {
        return call_user_func(array($caller, 'replaceStrings'), $content);
    }

    public static function unescapeSurrogates($content, $caller)
    {
        $subst = constant($caller.'::SUBST');

        return array(
            $subst.$subst,
            $subst,
        );
    }
}
