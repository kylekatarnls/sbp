<?php

namespace Sbp\Laravel;

use \Sbp\Sbp;

class ClassLoader extends \Illuminate\Support\ClassLoader
{
    /**
     * Load the given class file.
     *
     * @param  string  $class
     * @return void
     */
    public static function load($class)
    {
        $class = static::normalizeClass($class);

        foreach (static::$directories as $directory)
        {
            if (Sbp::fileExists($directory.DIRECTORY_SEPARATOR.$class, $path))
            {
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
    public static function register($prepend = true, $callback = null)
    {
        if ( ! static::$registered)
        {
            static::$registered = spl_autoload_register(array('\\Sbp\\Laravel\\ClassLoader', 'load'), true, $prepend);
            $app = $storage = __DIR__.'/../../../../../../app/';
            Sbp::writeIn(Sbp::SAME_DIR);
            Sbp::fileExists($app.'routes');
            $storage = $app . 'storage/sbp';
            if( ! file_exists($storage))
            {
                mkdir($storage, 0777);
                file_put_contents($storage . '/.gitignore', "*\n!.gitignore");
            }
            Sbp::writeIn($storage, $callback);
        }
    }
}
