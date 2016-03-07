<?php

namespace Sbp\Plugins\Core;

class Compiler
{
    protected static $validExpressionRegex = null;

    public static function __replaceSuperMethods($content, $caller)
    {
        $method = explode('::', __METHOD__);
        $subst = constant($caller.'::SUBST');
        $value = constant($caller.'::VALUE');
        $valueRegexNonCapturant = preg_quote($subst.$value).'[0-9]+'.preg_quote($value.$subst);

        $prevContent = null;
        while ($content !== $prevContent) {
            $prevContent = $content;
            $content = preg_replace_callback(
                '#(?<!-->)('. constant($caller.'::PARENTHESES').'->.+?'.$valueRegexNonCapturant.'|new\s+\\Sbp\\Handler.+?'.$valueRegexNonCapturant.'|'.static::$validExpressionRegex.'|'.constant($caller.'::VALIDVAR').')-->#',
                function ($match) use ($method, $caller) {
                    return '(new \\Sbp\\Handler('.call_user_func($method, $match[1], $caller).'))->';
                },
                $content
            );
        }

        return $content;
    }

    public static function restoreStrings($content, $caller)
    {
        $subst = constant($caller.'::SUBST');
        $value = constant($caller.'::VALUE');
        $number = constant($caller.'::NUMBER');
        $allowAloneCustomOperator = constant($caller.'::ALLOW_ALONE_CUSTOM_OPERATOR');

        $valuesContent = $content;
        $values = array();
        $validSubst = call_user_func(array($caller, 'getValidStringSurrogates'));
        $valueRegex = preg_quote($subst.$value).'([0-9]+)'.preg_quote($value.$subst);
        $valueRegexNonCapturant = preg_quote($subst.$value).'[0-9]+'.preg_quote($value.$subst);
        $validExpressionRegex = '(?<![a-zA-Z0-9_\x7f-\xff\$\\\\])(?:[a-zA-Z0-9_\x7f-\xff\\\\]+(?:'.$valueRegexNonCapturant.')+|\$+[a-zA-Z0-9_\x7f-\xff\\\\]+(?:'.$valueRegexNonCapturant.')*|'.$valueRegexNonCapturant.'|'.$validSubst.'|[\\\\a-zA-Z_][\\\\a-zA-Z0-9_]*|'.$number.')';
        static::$validExpressionRegex = $validExpressionRegex;
        $restoreValues = function ($content) use (&$values, $value, $subst) {
            foreach ($values as $id => &$string) {
                if ($string !== false) {
                    $old = $content;
                    $content = str_replace($subst.$value.$id.$value.$subst, $string, $content);
                    if ($old !== $content) {
                        $string = false;
                    }
                }
            }

            return $content;
        };
        $aloneCustomOperator = implode('|', array_map(
            function ($value) {
                return '(?<![a-zA-Z0-9_])'.$value;
            },
            explode('|', $allowAloneCustomOperator)
        ));

        static $previousKeyWords = null;
        static $keyWords = null;

        $phpWords = constant($caller.'::PHP_WORDS');
        $operators = constant($caller.'::OPERATORS');
        $mustCloseBlocks = constant($caller.'::MUST_CLOSE_BLOKCS');
        $blocks = constant($caller.'::BLOKCS');

        if (is_null($previousKeyWords)) {
            $previousKeyWords = $phpWords.'|'.$operators.'|'.$mustCloseBlocks;
        }
        if (is_null($keyWords)) {
            $keyWords = $phpWords.'|'.$operators.'|'.$blocks;
        }

        $compiler = get_called_class();
        $filters = function ($content) use ($previousKeyWords, $keyWords, $aloneCustomOperator, $restoreValues, &$values, $valueRegex, $valueRegexNonCapturant, $validSubst, $validExpressionRegex, $caller, $value, $subst, $compiler) {
            $comp = constant($caller.'::COMP');

            $replacements = array();

            if (call_user_func(array($caller, 'hasPlugin'), 'Sbp\Plugins\Core\Regex')) {
                $replacements[Sbp\Plugins\Core\Regex::PATTERN] =
                    function ($match) use ($restoreValues, $caller) {
                        return call_user_func(array($caller, 'includeString'), $restoreValues($match[0]));
                    };
            }

            if (call_user_func(array($caller, 'hasPlugin'), 'Sbp\Plugins\Core\CustomOperators')) {
                $replacements[
                    '#(?<=^|[,\n=*\/\^%&|<>!+-]|'.$aloneCustomOperator.')[\n\t ]+'.
                    '(?!'.$keyWords.'|array|['.$subst.$value.$comp.'\[\]\(\)\{\}])'.
                    '([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[\t ]+'.
                    '(?!'.$keyWords.')('.$validExpressionRegex.')(?!::|[a-zA-Z0-9_\x7f-\xff])#'
                ] =
                    function ($match) use ($restoreValues, &$values, $value, $subst) {
                        list($all, $keyWord, $right) = $match;
                        $id = count($values);
                        $values[$id] = $restoreValues('('.$right.')');

                        return ' __sbp_'.$keyWord.$subst.$value.$id.$value.$subst;
                    };
                $replacements[
                    '#('.$validExpressionRegex.')(?<!'.$previousKeyWords.')[\t ]+'.
                    '(?!'.$keyWords.'|array|['.$subst.$value.$comp.'\[\]\(\)\{\}])'.
                    '([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[\t ]+'.
                    '(?!'.$keyWords.')('.$validExpressionRegex.')(?!::|[a-zA-Z0-9_\x7f-\xff])#'
                ] =
                    function ($match) use ($restoreValues, &$values, $value, $subst) {
                        list($all, $left, $keyWord, $right) = $match;
                        $id = count($values);
                        $values[$id] = $restoreValues('('.$left.', '.$right.')');

                        return ' __sbp_'.$keyWord.$subst.$value.$id.$value.$subst;
                    };
            }

            return call_user_func(array($compiler, '__replaceSuperMethods'),
                call_user_func(array($caller, 'replace'), $content, $replacements),
                $caller
            );
        };
        $substituteValues = function ($match) use ($restoreValues, &$values, $filters, $value, $subst) {
            $id = count($values);
            $values[$id] = $restoreValues($filters($match[0]));

            return $subst.$value.$id.$value.$subst;
        };
        do {
            $content = preg_replace_callback('#[\(\[][^\(\)\[\]]*[\)\]]#', $substituteValues, $content, -1, $count);
        } while ($count > 0);
        $content = $restoreValues($filters($content));

        return $content;
    }
}
