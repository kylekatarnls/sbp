<?php

namespace Sbp\Wrapper;

require __DIR__ . '/../vendor/autoload.php';

use Sbp\SbpException;
use Sbp\Sbp as InitialSbp;

class Sbp extends InitialSbp
{
    const TEST_GET_BENCHMARK_HTML = 'test-get-benchmark-html';
    const TEST_GET_LIST = 'test-get-list';

    static public function testFileContent($from)
    {
        return static::testContent(file_get_contents($from), $from);
    }

    static public function testContent($from, $file)
    {
        static::$lastParsedFile = $file;
        $content = self::parse($from);
        static::$lastParsedFile = null;

        return $content;
    }

    static public function benchmark($title = '')
    {
        static $list = null;
        if($title === static::TEST_GET_BENCHMARK_HTML) {
            return static::getBenchmarkHtml($list);
        }
        if($title === static::TEST_GET_LIST) {
            return $list;
        }

        return static::recordBenchmark($list, $title);
    }
}

class TestCase extends \PHPUnit_Framework_TestCase
{
    const WRAP_LINES = 5;
    const IGNORE_BRACES = true;

    protected function assertParse($from, $to, $message = null)
    {
        if (is_null($message)) {
            $message = "Sbp::parse(\"$from\") do not return \"$to\"";
        }
        $explode = explode("\n", $parsed = Sbp::parse("<?\n".$from), 2);
        $from = str_replace(array("\n", "\r", "\t", ' '), '', trim(end($explode)));
        $to = str_replace(array("\n", "\r", "\t", ' '), '', trim($to));

        return $this->assertSame($from, $to, $message.", it return\"$parsed\"\n\n");
    }

    static protected function matchContent($from, $to)
    {
        $trim = static::IGNORE_BRACES ? "{}\n\r\t " : "\n\r\t ";
        $out = trim(file_get_contents($fromPath = $from));
        $in = trim(Sbp::testFileContent($toPath = preg_replace('#^(.+)(/[^/]+)$#', '$1/.src$2', $from)));
        $to = str_replace(array("\n", "\r", "\t", ' '), '', $out);
        $from = str_replace(array("\n", "\r", "\t", ' '), '', $in);
        $cleaner = function ($value) use ($trim) {
            $value = preg_replace('#/\*.*\*/#U', '', trim($value, $trim));
            $value = preg_replace('#\s*[\[\]\(\)\{\},!]\s*#', '$1', $value);
            return $value;
        };
        $to = $cleaner($to);
        $from = $cleaner($from);
        $lastDiffKey = -2 * static::WRAP_LINES;
        $lastPrintedKey = $lastDiffKey;
        if ($from !== $to) {
            echo "\n=====================================\n- $toPath (parsed)\n+ $fromPath\n";
            foreach (array('in', 'out') as $var) {
                $$var = preg_split('#\r\n|\r|\n#', preg_replace('#(\n[\t ]*)(\n[\t ]*)}([\t ]*)(?=\S)#', '$1}$2$3', $$var));
            }
            foreach ($in as $key => $line) {
                if ($cleaner($line) === $cleaner(isset($out[$key]) ? $out[$key] : '')) {
                    if ($key - $lastDiffKey === static::WRAP_LINES) {
                        echo " [...]\n";
                    } elseif ($key - $lastDiffKey < static::WRAP_LINES && $key) {
                        echo " ".str_replace("\t", '    ', $line)."\n";
                        $lastPrintedKey = $key;
                    }
                } else {
                    for ($i = max(0, $lastPrintedKey + 1, $key - static::WRAP_LINES); $i < $key; $i++) {
                        echo " ".str_replace("\t", '    ', $in[$i])."\n";
                    }
                    echo "-".str_replace("\t", '    ', $line)."\n";
                    echo "+".str_replace("\t", '    ', $out[$key])."\n";
                    $lastDiffKey = $key;
                    $lastPrintedKey = $key;
                }
            }
        }

        return ($from === $to);
    }

    protected function assertParseFile($from, $message = null, $keepMessage = null)
    {
        if (is_null($message)) {
            $message = "Sbp::fileParse(\"$from\") do match the compiled file";
        }
        if (is_null($keepMessage)) {
            $keepMessage = "Normal PHP in compiled \"$from\" must be keeped intact if reparsed";
        }
        $to = preg_replace('#^(.+)([/\\\\])([^/\\\\]+)$#', '$1$2.src$3', $from);
        $this->assertTrue(static::matchContent($from, $to), $message);
        $this->assertTrue(static::matchContent($from, $from), $keepMessage);
    }
}

class TestCompileCase extends \PHPUnit_Framework_TestCase
{
    protected function getTmp()
    {
        static $tmp = null;
        if (is_null($tmp)) {
            $tmp = __DIR__ . '/../.tmp';
            if (!file_exists($tmp)) {
                $this->createTempDirectory();
            }
        }
        return $tmp . '/';
    }

    /**
     * @before
     */
    public function createTempDirectory()
    {
        $tmp = $this->getTmp();
        if (!is_dir($tmp)) {
            mkdir($tmp);
        }
        InitialSbp::writeIn($tmp, function ($name) {
            $slash = strrpos($name, '/');

            return '_' . substr($name, $slash + 1);
        });
    }

    /**
     * @after
     */
    public function removeTempDirectory()
    {
        $tmp = $this->getTmp();
        InitialSbp::writeIn(InitialSbp::SAME_DIR, null);
        foreach (scandir($tmp) as $file) {
            if (substr($file, 0, 1) === '_') {
                unlink($tmp . $file);
            }
        }
        rmdir($tmp);
    }
}
