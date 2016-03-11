<?php

namespace Sbp\Plugins\Core;

class Indentation
{
    protected static function findLastBlock(&$line, array $blocks)
    {
        $position = false;
        foreach ($blocks as $block) {
            if (preg_match('#(?<![a-zA-Z0-9$_])'.$block.'(?![a-zA-Z0-9_])#s', $line, $match, PREG_OFFSET_CAPTURE)) {
                $pos = $match[0][1] + 1;
                if ($position === false || $pos > $position) {
                    $position = $pos;
                }
            }
        }

        return $position;
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
            $indent = $close - $open;
            $content = ' '.implode("\n", array_slice($grouped, 0, $iRead));
            while ($indent !== 0) {
                $open = strrpos($content, '(') ?: 0;
                $close = strrpos($content, ')') ?: 0;
                if ($open > $close) {
                    $indent--;
                    $content = substr($content, 0, $open);
                } else {
                    $indent++;
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
        $inInterface = false;
        foreach ($content as $index => &$line) {
            if (trim($line) !== '') {
                $espaces = strlen(str_replace("\t", '    ', $line)) - strlen(ltrim($line));
                $indent = empty($curind) ? -1 : end($curind);
                if ($inInterface !== false && $indent <= $inInterface) {
                    $inInterface = false;
                }
                if (preg_match('`^\s*interface\s`', $previousRead)) {
                    $inInterface = $indent;
                }
                if (($espaces <= $indent || ($indent === -1 && $espaces === 0)) && preg_match('#(?<![a-zA-Z0-9_\x7f-\xff$\(])('.constant($caller.'::ALLOW_EMPTY_BLOCKS').')(?![a-zA-Z0-9_\x7f-\xff])#', $previousRead) && strpos($previousRead, '{') === false) {
                    $previousRead .= preg_match('`^\s*namespace\s`', $previousRead) || (preg_match('`^\s*([a-zA-Z_]+\s+)*function\s`', $previousRead) && $inInterface !== false) ? ';' : ' {}';
                }
                if ($espaces > $indent) {
                    if (static::isBlock($previousRead, $content, $iRead, constant($caller.'::BLOCKS'))) {
                        if (substr(rtrim($previousRead), -1) !== '{'
                        && substr(ltrim($line), 0, 1) !== '{') {
                            $curind[] = $espaces;
                            $previousRead .= '{';
                        }
                    }
                } elseif ($espaces < $indent) {
                    while ($espaces < ($pop = end($curind))) {
                        if (trim($previousWrite, "\t }") === '') {
                            if (strpos($previousWrite, '}') === false) {
                                $previousWrite = str_repeat(' ', $espaces);
                            }
                            $previousWrite .= '}';
                        } else {
                            $length = strlen(ltrim($line));
                            if ($length && ($diff = strlen($line) - $length) > 0) {
                                $line = substr($line, 0, $diff).'} '.substr($line, $diff);
                            } else {
                                $line = '}'.$line;
                            }
                        }
                        array_pop($curind);
                    }
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
