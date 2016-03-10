<?php

namespace Sbp\Plugins\Core;

class Comparisons
{
    public static $comparisons = array(
        '#\seq\s#' => ' == ',
        '#\sne\s#' => ' != ',
        '#\sisnt\s#' => ' !== ',
        '#\sis\s#' => ' === ',
        '#\snot\s#' => ' !== ',
        '#\slt\s#' => ' < ',
        '#\sgt\s#' => ' > ',
    );
}
