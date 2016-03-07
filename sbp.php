<?php

require_once 'vendor/autoload.php';

use Sbp\Sbp;

define('CREATE_TEST', 'create-test');
define('COMPILE_TEST', 'compile-test');

function argn($i)
{
    global $argv;

    return isset($argv[$i]) ? $argv[$i] : null;
}

$script = argn(0);

switch ($command = argn(1)) {
    case CREATE_TEST:
        $name = argn(2);
        $path = __DIR__.'/tests/sbp/files/.src/'.$name.'.php';
        touch($path);
        $path = realpath($path);
        echo "You can now edit the SBP test file:\n";
        echo "$path\n";
        echo "Then enter the following command to compile the PHP file:\n";
        echo "php $script compile-test $name\n";
        break;

    case COMPILE_TEST:
        $name = argn(2);
        Sbp::fileParse(
            __DIR__.'/tests/sbp/files/.src/'.$name.'.php',
            $to = __DIR__.'/tests/sbp/files/'.$name.'.php'
        );
        echo "Compilation done in:\n";
        echo realpath($to)."\n";
        echo filesize($to)." bytes\n";
        break;

    default:
        echo "Usage:\n  php $script <command>\n\n";
        echo "Commands:\n  ".CREATE_TEST."\n  ".COMPILE_TEST;
        break;
}
