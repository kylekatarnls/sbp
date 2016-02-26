<?php

namespace Sbp\Plugins\Core;

class ArrayShortSyntax
{
    /**********************/
    /* Array short syntax */
    /**********************/
    public static function getArrayShortSyntax($content, $caller)
    {
        return array(
            '#{(\s*(?:\n+[\t ]*'.constant($caller.'::VALIDNAME').'[\t ]*=[^\n]+)+\s*)}#',
            array($caller, 'arrayShortSyntax'),
        );
    }
}
