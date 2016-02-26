<?php

namespace Sbp\Plugins\Core;

class Summons
{
    public static function getStarsSummons($content, $caller)
    {
        $validName = constant($caller.'::VALIDNAME');

        return array(
            // $var **= func() to $var = func($var)
            '#(\$.*\S)\s*\*\*=\s*('.$validName.')\s*\(\s*\)#U' => "$1 = $2($1)",

            // $var **= func(x, y) to $var = func($var, x, y)
            '#(\$.*\S)\s*\*\*=\s*('.$validName.')\s*\(#U' => "$1 = $2($1, ",

            // func(**$var, x, y) to $var = func($var, x, y)
            '#([\(;\s\.+/*=\r\n]\s*)('.$validName.')\s*\(\s*\*\*\s*(\$[^\),]+)#' => "$1$3 = $2($3",
        );
    }

    public static function getOperatorsSummons($content, $caller)
    {
        $operators = constant($caller.'::OPERATORS');

        return array(
            // $a (or= foo) to $a = ($a or foo)
            '#(\$.*\S)\s*\(\s*('.$operators.')=\s*(\S)#U' => "$1 = ($1 $2 $3",

            // $a or= foo to $a = $a or foo
            '#(\$.*\S)\s*('.$operators.')=\s*(\S)#U' => "$1 = $1 $2 $3",
        );
    }

    public static function getVarHandles($content, $caller)
    {
        $validVar = constant($caller.'::VALIDVAR');

        return array(
            '#('.$validVar.')\s*\!\?==\s*(\S[^;\n\r]*);#U' => "if (!isset($1)) { $1 = $4; }",

            '#('.$validVar.')\s*\!\?==\s*(\S[^;\n\r]*)(?=[;\n\r]|\$)#U' => "if (!isset($1)) { $1 = $4; }",

            '#('.$validVar.')\s*\!\?=\s*(\S[^;\n\r]*);#U' => "if (!$1) { $1 = $4; }",

            '#('.$validVar.')\s*\!\?=\s*(\S[^;\n\r]*)(?=[;\n\r]|\$)#U' => "if (!$1) { $1 = $4; }",

            '#('.$validVar.')\s*<->\s*('.$validVar.')#U' => "\$_sv = $4; $4 = $1; $1 = \$_sv; unset(\$_sv)",

            '#('.$validVar.')((?:\!\!|\!|~)\s*)(?=[\r\n;])#U'=> "$1 = $4$1",
        );
    }
}
