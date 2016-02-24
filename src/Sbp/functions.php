<?php

/**
 * To call SBP in a functional way.
 */

function sbp_include($file, $once = false)
{
    $method = $once ? 'includeOnceFile' : 'includeFile';
    return Sbp\Sbp::$method($file);
}


function sbp_include_once($file)
{
    return Sbp\Sbp::includeOnceFile($file);
}


function sbp($file, $once = false)
{
    return sbp_include($file, $once);
}


function sbp_include_if_exists($file, $once = false)
{
    try {
        return sbp_include($file, $once);
    } catch(Sbp\SbpException $e) {
        return false;
    }
}

function sbp_benchmark($title = '')
{
    Sbp\Sbp::benchmark($title);
}

function sbp_benchmark_end()
{
    Sbp\Sbp::benchmarkEnd();
}

function sbp_add_plugin($plugin, $from, $to = null)
{
    Sbp\Sbp::addPlugin($plugin, $from, $to);
}

function sbp_remove_plugin($plugin)
{
    Sbp\Sbp::removePlugin($plugin);
}
