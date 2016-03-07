<?php

use Sbp\Sbp;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    public function testInclude()
    {
        $tmp = __DIR__ . '/../../../.tmp/';
        if (!is_dir($tmp)) {
            mkdir($tmp);
        }
        copy(__DIR__ . '/../files/.src/return.php', $tmp . '_return.sbp.php');
        copy(__DIR__ . '/../files/.src/return.php', $tmp . '_return.bis.sbp.php');
        Sbp::writeIn($tmp, function ($name) {
            $slash = strrpos($name, '/');

            return '_' . substr($name, $slash + 1);
        });
        $this->assertSame(42, sbp($tmp . '_return'), 'sbp function shoudl include and return the SBP file result.');
        $this->assertSame(42, sbp($tmp . '_return'), 'sbp function shoudl works several time.');
        $this->assertSame(42, sbp_include_once($tmp . '_return.bis'), 'sbp_include_once function shoudl works only once.');
        $this->assertTrue(sbp_include_once($tmp . '_return.bis'), 'sbp_include_once function shoudl works only once.');
        foreach (scandir($tmp) as $file) {
            if (substr($file, 0, 1) === '_') {
                unlink($tmp . $file);
            }
        }
        rmdir($tmp);
    }

    /**
     * @expectedException Exception
     */
    public function testException()
    {
        sbp('i-do-not-exists');
    }

    public function testSoftError()
    {
        $this->assertFalse(sbp_include_if_exists('i-do-not-exists'));
    }

    public function testBenchmark()
    {
        $tmp = __DIR__ . '/../../../.tmp/';
        if (!is_dir($tmp)) {
            mkdir($tmp);
        }
        copy(__DIR__ . '/../files/.src/short_tag.php', $tmp . '_short_tag.sbp.php');
        Sbp::writeIn($tmp, function ($name) {
            $slash = strrpos($name, '/');

            return '_' . substr($name, $slash + 1);
        });
        sbp_benchmark('foo');
        sbp($tmp . '_short_tag');
        $contents = sbp_benchmark_end();
        foreach (scandir($tmp) as $file) {
            if (substr($file, 0, 1) === '_') {
                unlink($tmp . $file);
            }
        }
        rmdir($tmp);
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
