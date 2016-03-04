<?php

namespace Sbp\Plugins\Core;

class DefinedFunction
{
    public static function compileDefinedFunctions($content, $caller)
    {
        return array(
            '#if-defined-(function\s+('.constant($caller.'::VALIDNAME').')([^\{]*)'.constant($caller.'::BRACES').')#',
            'if (! function_exists(\'$2\')) { $1 }',
        );
    }
}
