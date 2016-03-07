<?php

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
}
