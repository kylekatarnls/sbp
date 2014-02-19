<?php namespace sbp\laravel;

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
			if (\sbp\sbp::fileExists($path = $directory.DIRECTORY_SEPARATOR.$class))
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
		}
	}
}