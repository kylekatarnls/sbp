<?php

namespace Sbp;

use Sbp\ValueHandler;

class Handler extends ValueHandler
{
    public function __call($method, $args)
    {
        if (is_object($this->value) && method_exists($this->value, $method)) {
            return call_user_func_array(array($this->value, $method), $args);
        }
        if (is_string($this->value)) {
            if (function_exists('preg_' . $method)) {
                $function = 'preg_' . $method;
                switch($method) {
                    case 'replace':
                    case 'filter':
                    case 'replace_callback':
                        $args = array_merge(
                            array_slice($args, 0, 2),
                            array($this->value),
                            array_slice($args, 2)
                        );
                        break;

                    case 'split':
                    case 'match':
                    case 'match_all':
                        $args = array_merge(
                            array_slice($args, 0, 1),
                            array($this->value),
                            array_slice($args, 1)
                        );
                        break;

                    default:
                        array_unshift($args, $this->value);
                        break;
                }
                return call_user_func_array($function, $args);
            } elseif (function_exists('str_' . $method)) {
                $function = 'str_' . $method;
                array_unshift($args, $this->value);
                return call_user_func_array($function, $args);
            }
        } elseif (is_array($this->value)) {
            if (function_exists('array_' . $method)) {
                $function = 'array_' . $method;
                array_unshift($args, $this->value);
                return call_user_func_array($function, $args);
            }
        }
        array_unshift($args, $this->value);
        return call_user_func_array($method, $args);
    }
}
