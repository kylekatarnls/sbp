<?php

namespace Sbp\Plugins\Core;

class Indentation
{
    protected static function mustClose(&$line, &$previousRead, $caller)
    {
        if (preg_match('#(?<![a-zA-Z0-9_\x7f-\xff$\(])('.constant($caller.'::ALLOW_EMPTY_BLOCKS').')(?![a-zA-Z0-9_\x7f-\xff])#', $previousRead)) {
            if (preg_match('#(?<![a-zA-Z0-9_\x7f-\xff$\(])('.constant($caller.'::BLOCKS').')(?![a-zA-Z0-9_\x7f-\xff])#', $line)) {
                return true;
            }
        }

        return false;
    }

    protected static function findLastBlock(&$line, array $blocks)
    {
        $pos = false;
        foreach ($blocks as $block) {
            if (preg_match('#(?<![a-zA-Z0-9$_])'.$block.'(?![a-zA-Z0-9_])#s', $line, $match, PREG_OFFSET_CAPTURE)) {
                $p = $match[0][1] + 1;
                if ($pos === false || $p > $pos) {
                    $pos = $p;
                }
            }
        }

        return $pos;
    }

    protected static function isBlock(&$line, &$grouped, $iRead, $pattern)
    {
        if (substr(rtrim($line), -1) === ';') {
            return false;
        }
        $blocks = explode('|', $pattern);
        $find = static::findLastBlock($line, $blocks);
        $pos = $find ?: 0;
        $open = substr_count($line, '(', $pos);
        $close = substr_count($line, ')', $pos);
        if ($open > $close) {
            return false;
        }
        if ($open < $close) {
            $c = $close - $open;
            $content = ' '.implode("\n", array_slice($grouped, 0, $iRead));
            while ($c !== 0) {
                $open = strrpos($content, '(') ?: 0;
                $close = strrpos($content, ')') ?: 0;
                if ($open === 0 && $close === 0) {
                    return false;
                }
                if ($open > $close) {
                    $c--;
                    $content = substr($content, 0, $open);
                } else {
                    $c++;
                    $content = substr($content, 0, $close);
                }
            }
            $content = substr($content, 1);
            $find = static::findLastBlock($content, $blocks);
            $pos = $find ?: 0;

            return $find !== false && !preg_match('#(?<!->)\s*\{#U', substr($content, $pos));
        }

        return $find !== false && !preg_match('#(?<!->)\s*\{#U', substr($line, $pos));
    }

    /**
     * Add { on indent and } on outdent.
     */
    public static function addBracesOnIndent($content, $caller)
    {
        $content = explode("\n", $content);
        $curind = array();
        $previousRead = '';
        $previousWrite = '';
        $iRead = 0;
        $iWrite = 0;
        foreach ($content as $index => &$line) {
            if (trim($line) !== '') {
                $espaces = strlen(str_replace("\t", '    ', $line)) - strlen(ltrim($line));
                $c = empty($curind) ? -1 : end($curind);
                if ($espaces > $c) {
                    if (static::mustClose($line, $previousRead, $caller)) {
                        $previousRead .= '{}';
                    } elseif (static::isBlock($previousRead, $content, $iRead, constant($caller.'::BLOCKS'))) {
                        if (substr(rtrim($previousRead), -1) !== '{'
                        && substr(ltrim($line), 0, 1) !== '{') {
                            $curind[] = $espaces;
                            $previousRead .= '{';
                        }
                    }
                } elseif ($espaces < $c) {
                    if ($c = substr_count($line, '}')) {
                        $curind = array_slice($curind, 0, -$c);
                    }
                    while ($espaces < ($pop = end($curind))) {
                        if (trim($previousWrite, "\t }") === '') {
                            if (strpos($previousWrite, '}') === false) {
                                $previousWrite = str_repeat(' ', $espaces);
                            }
                            $previousWrite .= '}';
                        } else {
                            $s = strlen(ltrim($line));
                            if ($s && ($d = strlen($line) - $s) > 0) {
                                $line = substr($line, 0, $d).'} '.substr($line, $d);
                            } else {
                                $line = '}'.$line;
                            }
                        }
                        array_pop($curind);
                    }
                } elseif (preg_match('#(?<![a-zA-Z0-9_\x7f-\xff$\(])('.constant($caller.'::MUST_CLOSE_BLOCKS').')(?![a-zA-Z0-9_\x7f-\xff])#', $previousRead)) {
                    $previousRead .= '{}';
                }
                $previousRead = &$line;
                $iRead = $index;
            }
            $previousWrite = &$line;
            $iWrite = $index;
        }
        $content = implode("\n", $content);
        if (!empty($curind)) {
            $braces = str_repeat('}', count($curind));
            $content .= substr($content, -1) === "\n" ? $braces."\n" : "\n".$braces;
        }

        return $content;
    }
}
