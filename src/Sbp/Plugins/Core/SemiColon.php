<?php

namespace Sbp\Plugins\Core;

class SemiColon
{
    /******************************/
    /* Complete with a semi-colon */
    /******************************/
    public static function addSemiColons($content, $caller)
    {
        $validComments = call_user_func(array($caller, 'getValidComments'));
        $validSubst = call_user_func(array($caller, 'getValidStringSurrogates'));
        $validHtml = call_user_func(array($caller, 'getHtmlCodes'));
        $beforeSemiColon = '('.$validSubst.'|\+\+|--|[a-zA-Z0-9_\x7f-\xff]!|[a-zA-Z0-9_\x7f-\xff]~|!!|[a-zA-Z0-9_\x7f-\xff\)\]])(?<!<\?php|<\?)';

        return array(
            '#'.$beforeSemiColon.'(\s*(?:'.$validComments.'\s*)*[\n\r]+\s*(?:'.$validComments.'\s*)*)(?=[a-zA-Z0-9_\x7f-\xff\$\}]|$)#U' => '$1;$2',

            '#'.$beforeSemiColon.'(\s*(?:'.$validComments.'\s*)*)$#U' => '$1;$2',

            '#'.$beforeSemiColon.'(\s*(?:'.$validComments.'\s*)*\?>)$#U' => '$1;$2',

            '#'.$beforeSemiColon.'(\s*(?:'.$validComments.'\s*)*'.$validHtml.')$#U' => '$1;$2',

            '#('.$validSubst.'|\+\+|--|[a-zA-Z0-9_\x7f-\xff]!|[a-zA-Z0-9_\x7f-\xff]~|!!|\]|\))(\s*\n\s*\()#U' => '$1;$2',
        );
    }
}
