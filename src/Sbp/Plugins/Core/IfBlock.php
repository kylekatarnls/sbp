<?php

namespace Sbp\Plugins\Core;

class IfBlock
{
    public static function addParenthesesToIfBlocks($content, $caller)
    {
        return array(
            '#(?<![a-zA-Z0-9_\x7f-\xff\$])('.constant($caller.'::IF_BLOCKS').')(?:[\t ]+(\S.*))?(?<!->)\s*\{#U',
            '$1 ($2) {',
        );
    }
}
