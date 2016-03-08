<?php

use Sbp\Laravel\ClassLoader;

class LoadTest extends \PHPUnit_Framework_TestCase
{
	public function testLaravelLoader()
	{
        if (!file_exists(sys_get_temp_dir().'/storage')) {
            mkdir(sys_get_temp_dir().'/storage');
        }
        if (!file_exists(sys_get_temp_dir().'/storage/sbp')) {
            mkdir(sys_get_temp_dir().'/storage/sbp');
        }
        if (!file_exists(sys_get_temp_dir().'/controllers')) {
            mkdir(sys_get_temp_dir().'/controllers');
        }
        ClassLoader::register(true, 'sha1', sys_get_temp_dir().'/');
        ClassLoader::addDirectories(array(sys_get_temp_dir().'/controllers'));
        file_put_contents(sys_get_temp_dir().'/controllers/FooBar.sbp.php', '<?'."\nFooBar\n\t+ \$a = 42");
        $foo = new FooBar();
        $this->assertSame(42, $foo->a);
        foreach (array(sys_get_temp_dir().'/controllers', sys_get_temp_dir().'/storage/sbp') as $directory) {
            if (file_exists($directory)) {
                foreach (scandir($directory) as $file) {
                    if (substr($file, 0, 1) !== '.') {
                        unlink($directory.'/'.$file);
                    }
                }
                rmdir($directory);
            }
        }
        if (file_exists(sys_get_temp_dir().'/storage')) {
            rmdir(sys_get_temp_dir().'/storage');
        }
	}
}
