<?php

namespace Sbp\Plugins\Core;

class Attributes
{
    public static function attributesShortCuts($content, $caller)
    {
        $start = constant($caller.'::START');
        $validName = constant($caller.'::VALIDNAME');
        $validComments = call_user_func(array($caller, 'getValidComments'));

        return array(
            '#'.$start.'-\s*(('.$validComments.'\s*)*\$'.$validName.')#U' => '$1private $2',

            '#'.$start.'\+\s*(('.$validComments.'\s*)*\$'.$validName.')#U' => '$1public $2',

            '#'.$start.'\*\s*(('.$validComments.'\s*)*\$'.$validName.')#U' => '$1protected $2',

            '#'.$start.'s-\s*(('.$validComments.'\s*)*\$'.$validName.')#U' => '$1static private $2',

            '#'.$start.'s\+\s*(('.$validComments.'\s*)*\$'.$validName.')#U' => '$1public static $2',

            '#'.$start.'s\*\s*(('.$validComments.'\s*)*\$'.$validName.')#U' => '$1protected static $2',
        );
    }
}
