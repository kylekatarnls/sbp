<?php

use Sbp\Laravel\ClassLoader;
use Sbp\Sbp;
use Sbp\SbpException;
use PHPUnit\Framework\TestCase;

class ClassLoaderClone extends ClassLoader
{
    public static function unregister()
    {
        static::$registered = false;
    }
}

class LoadTest extends TestCase
{
    public function testLaravelLoader()
    {
        if (!file_exists(sys_get_temp_dir().'/storage')) {
            mkdir(sys_get_temp_dir().'/storage');
        }
        if (!file_exists(sys_get_temp_dir().'/controllers')) {
            mkdir(sys_get_temp_dir().'/controllers');
        }
        ClassLoader::register(false, 'sha1', sys_get_temp_dir().'/');
        ClassLoader::addDirectories(array(sys_get_temp_dir().'/controllers'));
        file_put_contents(sys_get_temp_dir().'/controllers/FooBar.sbp.php', '<?'."\nFooBar\n\t+ \$a = 42");
        $foo = new FooBar();
        $this->assertSame(42, $foo->a);
        foreach (array(sys_get_temp_dir().'/controllers', sys_get_temp_dir().'/storage/sbp') as $directory) {
            if (file_exists($directory)) {
                foreach (scandir($directory) as $file) {
                    if (is_file($directory.'/'.$file)) {
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

    public function testDefaultStorageDirectory()
    {
        ClassLoaderClone::unregister();
        $message = null;
        try {
            ClassLoaderClone::register();
        } catch (SbpException $e) {
            $message = $e->getMessage();
        }
        $app = __DIR__.'/../../../../../../app';
        if (file_exists($app.'/storage') && is_writable($app.'/storage')) {
            $this->assertSame(realpath(dirname(Sbp::phpFile('foo'))), realpath($app.'/storage/sbp'), 'PHP files should be stored in the sbp storage directory in the Laravel app directory');

            return;
        }
        $this->assertTrue(strpos($message, 'register') !== false, 'Register method shoudl throw an exception no valid app path is found');
    }
}
