<?php

namespace Sbp;

class Handler extends ValueHandler
{
    protected function callOnStringValue($method, $args)
    {
        if (function_exists('preg_'.$method)) {
            $function = 'preg_'.$method;
            switch ($method) {
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
        } elseif (function_exists('str_'.$method)) {
            $function = 'str_'.$method;
            array_unshift($args, $this->value);
        }

        return call_user_func_array($function, $args);
    }

    public function match($pattern = null, &$matches = null, $flags = 0, $offset = 0)
    {
        if (is_string($this->value)) {
            return preg_match($pattern, $this->value, $matches, $flags, $offset);
        }

        return $this->__call('match', func_get_args());
    }

    public function match_all($pattern = null, &$matches = null, $flags = PREG_PATTERN_ORDER, $offset = 0)
    {
        if (is_string($this->value)) {
            return preg_match_all($pattern, $this->value, $matches, $flags, $offset);
        }

        return $this->__call('match_all', func_get_args());
    }

    public function __call($method, $args)
    {
        if (is_object($this->value) && method_exists($this->value, $method)) {
            return call_user_func_array(array($this->value, $method), $args);
        }
        if (is_string($this->value)) {
            return $this->callOnStringValue($method, $args);
        } elseif (is_array($this->value)) {
            if (function_exists('array_'.$method)) {
                $function = 'array_'.$method;
                array_unshift($args, $this->value);

                return call_user_func_array($function, $args);
            }
        }
        array_unshift($args, $this->value);

        return call_user_func_array($method, $args);
    }
}
