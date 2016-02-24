<?php

use sbp\wrapper\sbp;
use sbp\wrapper\testCase;

class pluginTest extends testCase
{
	public function testPlugin()
	{
		sbp_add_plugin('jQuery', '$(', 'new jQuery(');
		$this->assertParse("\$result = \$('#element')->animate({\n\tleft = 400\n\ttop = 200\n});", "\$result = new jQuery('#element')->animate(array(\n\t'left' => 400,\n\t'top' => 200\n));");
		sbp_remove_plugin('jQuery');
		$this->assertParse("\$result = \$('#element')->animate({\n\tleft = 400\n\ttop = 200\n});", "\$result = \$('#element')->animate(array(\n\t'left' => 400,\n\t'top' => 200\n));");
	}
}
