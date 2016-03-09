<?php

use Sbp\Sbp;
use Sbp\Wrapper\TestCompileCase;

class UtilsTest extends TestCompileCase
{
	public function testIsSbp()
	{
        $tmp = $this->getTmp();
        copy(__DIR__.'/../files/.src/return.php', $tmp.'_return.sbp.php');
        copy(__DIR__.'/../../bootstrap.php', $tmp.'_bootstrap.sbp.php');
        Sbp::fileParse($tmp.'_return.sbp.php', $tmp.'__return.sbp.php');
		$this->assertFalse(Sbp::isSbp($tmp.'_bootstrap.sbp.php'));
		$this->assertFalse(Sbp::isSbp($tmp.'_return.sbp.php'));
		$this->assertTrue(Sbp::isSbp($tmp.'__return.sbp.php'));
	}
}
