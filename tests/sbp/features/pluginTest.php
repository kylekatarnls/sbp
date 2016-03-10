<?php

use Sbp\SbpException;
use Sbp\Wrapper\Sbp;
use Sbp\Wrapper\TestCase;

class PluginTest extends TestCase
{
	public function testAddPlugin()
	{
		sbp_add_plugin('jQuery', '$(', 'new jQuery(');
		$this->assertParse("\$result = \$('#element')->animate({\n\tleft = 400\n\ttop = 200\n});", "\$result = new jQuery('#element')->animate(array(\n\t'left' => 400,\n\t'top' => 200\n));");
	}

	public function testRemovePlugin()
	{
		sbp_remove_plugin('jQuery');
		$this->assertParse("\$result = \$('#element')->animate({\n\tleft = 400\n\ttop = 200\n});", "\$result = \$('#element')->animate(array(\n\t'left' => 400,\n\t'top' => 200\n));");
	}

	/**
	 * @expectedException Exception
	 */
	public function testPluginClassError()
	{
		sbp_add_plugin('Class\That\Does\Not\Exists');
	}

	/**
	 * @expectedException Exception
	 */
	public function testPluginArgumentsError()
	{
		sbp_add_plugin('Foo', array(), 'bar');
	}

	public function testPluginNotCalled()
	{
		sbp_add_plugin('foo1', '`foo`', function () {
			throw new \Exception("Error Processing Request", 1);
		});
		$this->assertParse("\$bar = 1\necho \$bar", "\$bar = 1;\necho \$bar;");
		sbp_remove_plugin('foo1');
	}

	public function testPluginWithException()
	{
		$message = '';
		try {
			sbp_add_plugin('foo2', '`foo`', function () {
				throw new \Exception("Error Processing Request", 1);
			});
			Sbp::parse("<?\n\$foo = 1\necho \$foo");
		} catch (SbpException $e) {
			$message = $e->getMessage();
		}
		$this->assertTrue(strpos($message, 'Replacement') !== false);
		sbp_remove_plugin('foo2');
	}

	public function testPluginNotCallable()
	{
		$message = '';
		try {
			sbp_add_plugin('foo3', 'not_callable');
			Sbp::parse("<?\necho 'Hello'");
		} catch (SbpException $e) {
			$message = $e->getMessage();
		}
		$this->assertTrue(strpos($message, 'callable') !== false);
		sbp_remove_plugin('foo3');
	}
}
