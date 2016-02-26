<?php

namespace Sbp\Plugins\Core;

class Functions
{
    public static function functionShortCuts($content, $caller)
    {
        $start = constant($caller.'::START');
        $validName = constant($caller.'::VALIDNAME');

        return array(
            '#'.$start.'<(?![\?=])#' => '$1return ',

            '#'.$start.'@f[\t ]+('.$validName.')#' => '$1if-defined-function $2',

            '#'.$start.'f[\t ]+('.$validName.')#' => '$1function $2',

            '#(?<![a-zA-Z0-9_])f°[\t ]*\(#' => 'function(',

            '#(?<![a-zA-Z0-9_])f°([\t ]*(?:\n|\r|$))#' => 'function ()$1',

            '#(?<![a-zA-Z0-9_])f°([\t ]*(?:\$|use|\{|\n|$))#' => 'function$1',
        );
    }
}
