<?php

use Sbp\Sbp;
use Sbp\Wrapper\TestCompileCase;

class FunctionsTest extends TestCompileCase
{
    public function testInclude()
    {
        $tmp = $this->getTmp();
        copy(__DIR__ . '/../files/.src/return.php', $tmp . '_return.sbp.php');
        copy(__DIR__ . '/../files/.src/return.php', $tmp . '_return.bis.sbp.php');
        $this->assertSame(42, sbp($tmp . '_return'), 'sbp function shoudl include and return the SBP file result.');
        $this->assertSame(42, sbp($tmp . '_return'), 'sbp function shoudl works several time.');
        $this->assertSame(42, sbp_include_once($tmp . '_return.bis'), 'sbp_include_once function shoudl works only once.');
        $this->assertTrue(sbp_include_once($tmp . '_return.bis'), 'sbp_include_once function shoudl works only once.');
    }

    /**
     * @expectedException Sbp\SbpException
     */
    public function testExceptionInclude()
    {
        sbp('i-do-not-exists');
    }

    /**
     * @expectedException Sbp\SbpException
     */
    public function testExceptionIncludeOnce()
    {
        sbp_include('neither-do-i', true);
    }

    public function testSoftError()
    {
        $this->assertFalse(sbp_include_if_exists('i-do-not-exists'));
    }

    public function testBenchmark()
    {
        $tmp = $this->getTmp();
        copy(__DIR__ . '/../files/.src/short_tag.php', $tmp . '_short_tag.sbp.php');
        sbp_benchmark();
        sbp($tmp . '_short_tag');
        $contents = sbp_benchmark_end();
        $this->assertFalse(empty($contents), 'benchmark should provide some contents');
        $this->assertTrue(preg_match('`
            <html[^>]*>\s*<head>[\s\S]+?</head>\s*
                <body>\s*
                    <h1>Benckmark</h1>\s*
                    <ul>\s*
                        <li>Start\s+benchmark</li>\s*
                        <li><b>[^<]+</b>End\s+benchmark</li>\s*
                    </ul>\s*
                    <p>All:\s+<b>[^<]+</b></p>\s*
                    <h1>Code\s+source</h1>\s*
                    <pre>Hello\s+World!</pre>\s*
                </body>\s*
            </html>
        `x', $contents) > 0, 'benchmark return times in a HTML template');
    }
}
