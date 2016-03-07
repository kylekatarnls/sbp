<?php

use Sbp\Sbp;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
	public function testSuperMethods()
	{
		$tmp = __DIR__.'/../../../.tmp';
		if (!is_dir($tmp)) {
			mkdir($tmp);
		}
		copy(__DIR__.'/../files/.src/super_methods.php', $tmp.'/_super_methods.sbp.php');
		Sbp::writeIn($tmp, function ($name) {
			$slash = strrpos($name, '/');

			return '_'.substr($name, $slash + 1);
		});
        ob_start();
        sbp($tmp.'/_super_methods');
        $contents = ob_get_contents();
        ob_end_clean();
		$this->assertSame("2.2360679774998\n625\ntoto, tata, lulu\ntoto, tata, lulu\n", $contents);
		foreach (scandir($tmp) as $file) {
			if (substr($file, 0, 1) === '_') {
				unlink($tmp.'/'.$file);
			}
		}
		rmdir($tmp);
	}
}
