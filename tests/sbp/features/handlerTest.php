<?php

use Sbp\Sbp;
use Sbp\Wrapper\TestCompileCase;

class HandlerTest extends TestCompileCase
{
	public function testSuperMethods()
	{
        $tmp = $this->getTmp();
		copy(__DIR__.'/../files/.src/super_methods.php', $tmp.'_super_methods.sbp.php');
        ob_start();
        sbp($tmp.'_super_methods');
        $contents = ob_get_contents();
        ob_end_clean();
		$this->assertSame(
            "2.2360679774998\n625\ntoto, tata, lulu\ntoto, tata, lulu\n".
            "A###EF\nA9899100EF\nA???EF\n11\nArray\n(\n    [0] => 5\n    [1] => 3\n)\n".
            "1\n2\nyoh:oh-pouf-paf\n-p, -p\nfalse\ntrue\nfalse\nabc3\n",
            $contents
        );
	}

	public function testChainer()
	{
        $tmp = $this->getTmp();
		copy(__DIR__.'/../files/.src/chainer.php', $tmp.'_chainer.sbp.php');
        ob_start();
        sbp($tmp.'_chainer');
        $contents = ob_get_contents();
        ob_end_clean();
		$this->assertSame(
            "trucmachinchose",
            $contents
        );
	}

	public function testParseInTheSameFile()
	{
        $tmp = $this->getTmp();
		file_put_contents($tmp.'_foo.sbp.php', "<?\nif true\n\techo 'Hello'");
		Sbp::fileParse($tmp.'_foo.sbp.php');
		$lines = file($tmp.'_foo.sbp.php');
		unset($lines[0]);
		$contents = trim(implode('', $lines));
		$this->assertSame(
            "if (true) {\n\techo 'Hello';\n}",
            $contents
        );
	}
}
