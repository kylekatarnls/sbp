<?php

namespace Sbp\Plugins\Core;

class SwitchShortCuts
{
    public static function getSwitchShortCuts($content, $caller)
    {
        $validComments = call_user_func(array($caller, 'getValidComments'));

        return array(
            // value := to switch (value)
            '#(\n\s*(?:'.$validComments.'\s*)*)(\S.*)\s+\:=#U' => "$1switch($2)",

            // value :: to case value:
            '#(\n\s*(?:'.$validComments.'\s*)*)(\S.*)\s+\:\:#U' => "$1case $2:",

            // d: to default:
            '#(\n\s*(?:'.$validComments.'\s*)*)d\:#' => "$1default:",

            // :; to break;
            ':;' => "break;",
        );
    }
}
