<?php

use Sbp\Sbp;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
	public function testInclude()
	{
		$tmp = __DIR__ . '/../../../.tmp/';
		if (!is_dir($tmp)) {
			mkdir($tmp);
		}
		copy(__DIR__ . '/../files/.src/return.php', $tmp . '_return.sbp.php');
		copy(__DIR__ . '/../files/.src/return.php', $tmp . '_return.bis.sbp.php');
		Sbp::writeIn($tmp, function ($name) {
			$slash = strrpos($name, '/');

			return '_' . substr($name, $slash + 1);
		});
		$this->assertSame(42, sbp($tmp . '_return'), 'sbp function shoudl include and return the SBP file result.');
		$this->assertSame(42, sbp($tmp . '_return'), 'sbp function shoudl works several time.');
		$this->assertSame(42, sbp_include_once($tmp . '_return.bis'), 'sbp_include_once function shoudl works only once.');
		$this->assertSame(true, sbp_include_once($tmp . '_return.bis'), 'sbp_include_once function shoudl works only once.');
		foreach (scandir($tmp) as $file) {
			if (substr($file, 0, 1) === '_') {
				unlink($tmp . $file);
			}
		}
		rmdir($tmp);
	}
}
