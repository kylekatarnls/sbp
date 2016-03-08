<?php

require_once 'vendor/autoload.php';

use Sbp\Sbp;

define('CREATE_TEST', 'create-test');
define('COMPILE_TEST', 'compile-test');
define('COMPILE', 'compile');

function argn($i)
{
    global $argv;

    return isset($argv[$i]) ? $argv[$i] : null;
}

$script = argn(0);

if (substr($script, -4) === '.php') {
    $script = "php $script";
}

switch ($command = argn(1)) {
    case CREATE_TEST:
        $name = argn(2);
        $path = __DIR__.'/tests/sbp/files/.src/'.$name.'.php';
        touch($path);
        $path = realpath($path);
        echo "You can now edit the SBP test file:\n";
        echo "$path\n";
        echo "Then enter the following command to compile the PHP file:\n";
        echo "$script ".COMPILE_TEST." $name\n";
        break;

    case COMPILE_TEST:
        $name = argn(2);
        $from = realpath(__DIR__.'/tests/sbp/files/.src').DIRECTORY_SEPARATOR.$name.'.php';
        if (file_exists($from)) {
            $to = __DIR__.'/tests/sbp/files/'.$name.'.php';
            Sbp::fileParse($from, $to);
            echo "Compilation done in:\n";
            echo realpath($to)."\n";
            echo filesize($to)." bytes\n";
            break;
        }
        echo "File not found at:\n";
        echo $from."\n";
        echo "To create a new test with this name, enter:\n";
        echo "$script ".CREATE_TEST." $name \n";
        break;

    case COMPILE:
        $from = argn(2);
        if (file_exists($from)) {
            $to = tempnam(sys_get_temp_dir(), 'sbp-compile');
            Sbp::fileParse($from, $to);
            readfile($to);
            unlink($to);
            break;
        }
        echo "File not found at:\n";
        echo $from."\n";
        break;

    default:
        echo "Usage:\n  php $script <command>\n\n";
        echo "Commands:\n  ".CREATE_TEST."\n  ".COMPILE_TEST."\n  ".COMPILE;
        break;
}
