<?php namespace sbp\laravel

class ClassLoader extends Illuminate\Support\ClassLoader
{
	public static function load($class)
	{
		$class = static::normalizeClass($class);

		foreach (static::$directories as $directory)
		{
			if (\sbp::fileExists($path = $directory.DIRECTORY_SEPARATOR.$class))
			{
				require_once $path;

				return true;
			}
		}
	}
}