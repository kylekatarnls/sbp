<?php

namespace Sbp\Laravel;

use Sbp\Sbp;
use Sbp\SbpException;

class ClassLoader extends \Illuminate\Support\ClassLoader
{
    /**
     * Load the given class file.
     *
     * @param string $class
     *
     * @return void
     */
    public static function load($class)
    {
        $class = static::normalizeClass($class);

        foreach (static::$directories as $directory) {
            if (Sbp::fileExists($directory.DIRECTORY_SEPARATOR.$class, $path)) {
                require_once $path;

                return true;
            }
        }
    }

    /**
     * Register the given class loader on the auto-loader stack.
     *
     * @return void
     */
    public static function register($prepend = true, $callback = null, $app = null)
    {
        if (!static::$registered) {
            static::$registered = spl_autoload_register(array('\\Sbp\\Laravel\\ClassLoader', 'load'), true, $prepend);
            if (is_null($app)) {
                $app = __DIR__.'/../../../../../../app';
            }
            if (!file_exists($app.'/storage') || !is_writable($app.'/storage')) {
                throw new SbpException("Laravel app and/or writable storage directory not found at $app, please specify the path with the following code:\nSbp\\Laravel\\ClassLoader::register(true, 'sha1', \$laravelAppPath)");
            }
            Sbp::writeIn(Sbp::SAME_DIR);
            Sbp::fileExists($app.'/routes');
            $storage = $app.'/storage/sbp';
            if (!file_exists($storage)) {
                if (mkdir($storage, 0777)) {
                    file_put_contents($storage.'/.gitignore', "*\n!.gitignore");
                }
            }
            Sbp::writeIn($storage, $callback);
        }
    }
}
