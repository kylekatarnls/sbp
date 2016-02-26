<?php

/************************************
 * To call SBP in a functional way. *
 ************************************/

/**
 * Include an SBP file, compile it into PHP code and execute it.
 *
 * @param string file, input file path
 * @param bool   once, no re-include if true
 *
 * @throws \Sbp\SbpException if the file does not exist
 *
 * @return mixed|void
 */
function sbp_include($file, $once = false)
{
    $method = $once ? 'includeOnceFile' : 'includeFile';
    return Sbp\Sbp::$method($file);
}

/**
 * Alias of sbp_include.
 */
function sbp($file, $once = false)
{
    return sbp_include($file, $once);
}

/**
 * Include an SBP file if it have'nt been yet, compile it into PHP code and execute it.
 *
 * @param string file, input file path
 *
 * @throws \Sbp\SbpException if the file does not exist
 *
 * @return mixed|void
 */
function sbp_include_once($file)
{
    return Sbp\Sbp::includeOnceFile($file);
}

/**
 * Include an SBP file if it have'nt been yet, compile it into PHP code and execute it.
 * This one does not throws any exception even if the file does not exist.
 *
 * @param string file, input file path
 *
 * @return mixed|void
 */
function sbp_include_if_exists($file, $once = false)
{
    try {
        return sbp_include($file, $once);
    } catch(Sbp\SbpException $e) {
        return false;
    }
}

/**
 * Start a benchmark.
 *
 * @param string title, benchmark identifier name
 */
function sbp_benchmark($title = '')
{
    Sbp\Sbp::benchmark($title);
}

/**
 * Terminate the current benchmark.
 */
function sbp_benchmark_end()
{
    Sbp\Sbp::benchmarkEnd();
}

/**
 * Add a plug-in (custom rules) to SBP.
 *
 * @param string                plugin, name of the new rule or set of rules.
 * @param string|array|function from, if it's
 *
 * @throws \Sbp\SbpException if the to is specified but from is not a string.
 */
function sbp_add_plugin($plugin, $from, $to = null)
{
    Sbp\Sbp::addPlugin($plugin, $from, $to);
}

/**
 * Remove a plug-in (custom rules) from SBP.
 *
 * @param string plugin, name of the new rule or set of rules.
 */
function sbp_remove_plugin($plugin)
{
    Sbp\Sbp::removePlugin($plugin);
}
