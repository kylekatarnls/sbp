<?php namespace sbp\laravel;

use \sbp\sbp;

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
			if (sbp::fileExists($directory.DIRECTORY_SEPARATOR.$class, $path))
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
	public static function register($prepend = true)
	{
		if ( ! static::$registered)
		{
			static::$registered = spl_autoload_register(array('\sbp\laravel\ClassLoader', 'load'), true, $prepend);
			$app = $storage = __DIR__.'/../../../../../../app/';
			sbp::writeIn(sbp::SAME_DIR);
			sbp::fileExists($app.'routes');
			$storage = $app . 'storage/sbp';
			if( ! file_exists($storage))
			{
				mkdir($storage, 0777);
				file_put_contents($storage . '/.gitignore', "*\n!.gitignore");
			}
			sbp::writeIn($storage);
		}
	}
}