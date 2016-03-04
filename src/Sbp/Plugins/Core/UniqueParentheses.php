<?php

namespace Sbp\Plugins\Core;

class UniqueParentheses
{
    public static function dedupeParentheses($content, $caller)
    {
        $parentheses = constant($caller.'::PARENTHESES');

        return array(
            '#\(('.$parentheses.')\)#',
            '$1',
        );
    }
}
