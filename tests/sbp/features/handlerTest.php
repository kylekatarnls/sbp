<?php

use Sbp\Sbp;
use Sbp\Wrapper\TestCompileCase;

class HandlerTest extends TestCompileCase
{
	public function testSuperMethods()
	{
        $tmp = $this->tmp;
		copy(__DIR__.'/../files/.src/super_methods.php', $tmp.'/_super_methods.sbp.php');
        ob_start();
        sbp($tmp.'/_super_methods');
        $contents = ob_get_contents();
        ob_end_clean();
		$this->assertSame(
            "2.2360679774998\n625\ntoto, tata, lulu\ntoto, tata, lulu\n".
            "A###EF\n11\nArray\n(\n    [0] => 5\n    [1] => 3\n)\n".
            "1\n2\nyoh:oh-pouf-paf\n-p, -p\nfalse\ntrue\nfalse\nabc3\n",
            $contents
        );
	}

	public function testChainer()
	{
        $tmp = $this->tmp;
		copy(__DIR__.'/../files/.src/chainer.php', $tmp.'/_chainer.sbp.php');
        ob_start();
        sbp($tmp.'/_chainer');
        $contents = ob_get_contents();
        ob_end_clean();
		$this->assertSame(
            "trucmachinchose",
            $contents
        );
	}
}
