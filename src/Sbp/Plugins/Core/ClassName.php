<?php

namespace Sbp\Plugins\Core;

class ClassName
{
    /**************************/
    /* Parse class structure. */
    /**************************/
    public static function className($content, $caller)
    {
        return array(
            '#
            (
                (?:^|\S\s*)
                \n[\t ]*
            )
            (
                (?:
                    (?:'.constant($caller.'::ABSTRACT_SHORTCUTS').')
                    \s+
                )?
                \\\\?
                (?:'.constant($caller.'::VALIDNAME').'\\\\)*
                '.constant($caller.'::VALIDNAME').'
            )
            (?:
                (?::|\s+:\s+|\s+extends\s+)
                (
                    \\\\?
                    '.constant($caller.'::VALIDNAME').'
                    (?:\\\\'.constant($caller.'::VALIDNAME').')*
                )
            )?
            (?:
                (?:<<<|\s+<<<\s+|\s+implements\s+)
                (
                    \\\\?
                    '.constant($caller.'::VALIDNAME').'
                    (?:\\\\'.constant($caller.'::VALIDNAME').')*
                    (?:
                        \s*,\s*
                        \\\\?
                        '.constant($caller.'::VALIDNAME').'
                        (?:\\\\'.constant($caller.'::VALIDNAME').')*
                    )*
                )
            )?
            (
                \s*
                (?:{(?:.*})?)?
                \s*\n
            )
            #xi',
            array(get_class(), '__parseClass'),
        );
    }

    /***********************************/
    /* Parse class structure callback. */
    /***********************************/
    public static function __parseClass($match, $caller)
    {
        list($all, $start, $class, $extend, $implement, $end) = $match;
        $class = trim($class);
        if (in_array(substr($all, 0, 1), str_split(',(+-/*&|'))
        || in_array($class, array_merge(
            array('else', 'try', 'default:', 'echo', 'print', 'exit', 'continue', 'break', 'return', 'do'),
            explode('|', constant($caller.'::PHP_WORDS'))
        ))) {
            return $all;
        }
        $className = preg_replace('#^(?:'.constant($caller.'::ABSTRACT_SHORTCUTS').')\s+#', '', $class, -1, $isAbstract);
        $codeLine = $start.($isAbstract ? 'abstract ' : '').'class '.$className.
            (empty($extend) ? '' : ' extends '.trim($extend)).
            (empty($implement) ? '' : ' implements '.trim($implement)).
            ' '.trim($end);

        return $codeLine.str_repeat("\n", substr_count($all, "\n") - substr_count($codeLine, "\n"));
    }
}
