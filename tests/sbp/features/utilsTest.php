<?php

use Sbp\Plugins\Helpers\FileHelper;
use Sbp\Sbp;
use Sbp\SbpException;
use Sbp\Wrapper\TestCompileCase;

class FileEmulation
{
    function url_stat($path)
    {
        $mode = 0666;
        $uid = 0;
        $gid = 0;

        $len = strlen('fiemulate://');
        $type = substr($path, $len, 1);
        switch (substr($path, $len, 1)) {
            case 'u':
                $uid = getmyuid();
                break;
            case 'g':
                $gid = getmygid();
                break;
            case 'a':
                $uid = getmyuid();
                $gid = getmygid();
                break;
        }
        switch (substr($path, $len + 2)) {
            case 'not_readable':
                $mode = 0222;
                break;
            case 'not_writable':
                $mode = 0444;
                break;
        }
        $keys = array(
            'dev',
            'ino',
            'mode',
            'nlink',
            'uid',
            'gid',
            'rdev',
            'size',
            'atime',
            'mtime',
            'ctime',
            'blksize',
            'blocks',
        );
        $values = array(0, 0, $mode, 0, $uid, $gid, 0, 0, 0, 0, 0, 0, 0);
        foreach ($keys as $index => $key) {
            $values[$key] = $values[$index];
        }

        return $values;
    }
}

class UtilsTest extends TestCompileCase
{
    public function testIsSbp()
    {
        $tmp = $this->getTmp();
        copy(__DIR__.'/../files/.src/return.php', $tmp.'_return.sbp.php');
        copy(__DIR__.'/../../bootstrap.php', $tmp.'_bootstrap.sbp.php');
        Sbp::fileParse($tmp.'_return.sbp.php', $tmp.'__return.sbp.php');
        $this->assertFalse(Sbp::isSbp($tmp.'_bootstrap.sbp.php'));
        $this->assertFalse(Sbp::isSbp($tmp.'_return.sbp.php'));
        $this->assertTrue(Sbp::isSbp($tmp.'__return.sbp.php'));
    }

    public function testFileHelper()
    {
        $tmp = $this->getTmp();
        copy(__DIR__.'/../files/.src/return.php', $tmp.'_return.sbp.php');
        copy(__DIR__.'/../../bootstrap.php', $tmp.'_bootstrap.sbp.php');
        Sbp::fileParse($tmp.'_return.sbp.php', $tmp.'__return.sbp.php');
        $source = FileHelper::getSbpSource($tmp.'__return.sbp.php');
        $this->assertTrue(file_exists($source));
        $this->assertSame($source, FileHelper::cleanPath($tmp.'_return.sbp.php'));
    }

    protected function emulateFile($from, $to, $expected)
    {
        if (!in_array('fiemulate', stream_get_wrappers())) {
            stream_wrapper_register('fiemulate', 'FileEmulation');
        }
        $message = '';
        try {
            Sbp::fileParse('fiemulate://'.$from, 'fiemulate://'.$to);
        } catch (SbpException $e) {
            $message = $e->getMessage();
        }
        $this->assertTrue(strpos($message, 'chmod '.$expected) !== false, 'The following message does not contain "chmod '.$expected.'":'."\n".$message);
    }

    public function testFileParseExceptions()
    {
        $this->emulateFile('fiemulate://u_not_readable', 'fiemulate://open_dir/file', 'u+r');
        // if unix
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            $this->emulateFile('fiemulate://g_not_readable', 'fiemulate://open_dir/file', 'g+r');
            $this->emulateFile('fiemulate://o_not_readable', 'fiemulate://open_dir/file', 'o+r');
            $this->emulateFile('fiemulate://a_openfile', 'fiemulate://u_not_writable/file', 'u+w');
            $this->emulateFile('fiemulate://a_openfile', 'fiemulate://g_not_writable/file', 'g+w');
            $this->emulateFile('fiemulate://a_openfile', 'fiemulate://o_not_writable/file', 'o+w');
        }
    }
}
