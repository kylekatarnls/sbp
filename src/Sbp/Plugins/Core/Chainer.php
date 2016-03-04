<?php

namespace Sbp\Plugins\Core;

class Chainer
{
    /**********************/
    /* Array short syntax */
    /**********************/
    public static function getChainer($content, $caller)
    {
        return array(
            '#'.preg_quote(constant($caller.'::CHAINER')).'('.constant($caller.'::PARENTHESES').')#',
            '(new \\Sbp\\Chainer($1))',
        );
    }
}
