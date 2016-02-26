<?php

namespace Sbp\Plugins\Core;

class Methods
{
    public static function methodsShortCuts($content, $caller)
    {
        $start = constant($caller.'::START');
        $validName = constant($caller.'::VALIDNAME');
        $validComments = call_user_func(array($caller, 'getValidComments'));

        return array(
            '#'.$start.'\*[\t ]*(('.$validComments.'[\t ]*)*'.$validName.')#U' => '$1protected function $2',

            '#'.$start.'-[\t ]*(('.$validComments.'[\t ]*)*'.$validName.')#U' => '$1private function $2',

            '#'.$start.'\+[\t ]*(('.$validComments.'[\t ]*)*'.$validName.')#U' => '$1public function $2',

            '#'.$start.'s\*[\t ]*(('.$validComments.'[\t ]*)*'.$validName.')#U' => '$1protected static function $2',

            '#'.$start.'s-[\t ]*(('.$validComments.'[\t ]*)*'.$validName.')#U' => '$1static private function $2',

            '#'.$start.'s\+[\t ]*(('.$validComments.'[\t ]*)*'.$validName.')#U' => '$1public static function $2',
        );
    }
}
