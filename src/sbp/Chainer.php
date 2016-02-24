<?php

namespace Sbp;

use Sbp\ValueHandler;

class Chainer extends ValueHandler
{
    public function __call($method, $args)
    {
        call_user_func_array(array($this->value, $method), $args);

        return $this;
    }
}
