<?php

namespace Sbp\Plugins\Core;

class CompileFunctions
{
    public static $restoreFunction = array(
        '#(?<=^|\s)(function\s[^{]+);#U' => '$1 {}',

        '#(?<![a-zA-Z0-9_\x7f-\xff\$])function(\s[^{]*);#' => 'function$1 {}',

        '#(?<![a-zA-Z0-9_\x7f-\xff\$])function[\t ]+(array[\t ].+|_*[A-Z\$\&\\\\].+)?(?<!->)\s*\{#U' => 'function ($1) {',

        '#(?<![a-zA-Z0-9_\x7f-\xff\$])function\s+use(?![a-zA-Z0-9_\x7f-\xff])#U' => 'function () use',

        '#(?<![a-zA-Z0-9_\x7f-\xff\$])(function.*[^a-zA-Z0-9_\x7f-\xff\$])use[\t ]*((array[\t ].+|_*[A-Z\$\&\\\\].+)(?<!->)[\t ]*\{)#U' => '$1) use ($2',

        '#\((\([^\(\)]+\))\)#' => '$1',

        '#(catch\s*\([^\)]+\)\s*)([^\s\{])#' => '$1{} $2',
    );

    public static function functionShortCuts($content, $caller)
    {
        return array(
            '#(?<![a-zA-Z0-9_\x7f-\xff\$])(function[\t ]+'.constant($caller.'::VALIDNAME').')(?:[\t ]+(array[\t ].+|_*[A-Z\$\&\\\\].+))?(?<!->)\s*\{#U',
            '$1 ($2) {',
        );
    }
}
