<?php

use Sbp\Sbp;
use Sbp\Wrapper\TestCompileCase;

class ProdModeTest extends TestCompileCase
{
	public function testSuperMethods()
	{
        $tmp = $this->getTmp();
        Sbp::prod();
        copy(__DIR__.'/../files/.src/return.php', $tmp.'_return_prod.sbp.php');
        file_put_contents($tmp.'__return_prod.php', '<?php return 1138;');
        touch($tmp.'__return_prod.php', time() - 3600);
        $this->assertSame(1138, sbp_include_once($tmp.'_return_prod'));
        $this->assertSame(1138, sbp($tmp.'_return_prod'));
        Sbp::dev();
        copy(__DIR__.'/../files/.src/return.php', $tmp.'_return_dev.sbp.php');
        file_put_contents($tmp.'__return_dev.php', '<?php return 1138;');
        touch($tmp.'__return_dev.php', time() - 3600);
        $this->assertSame(42, sbp_include_once($tmp.'_return_dev'));
        $this->assertSame(42, sbp($tmp.'_return_dev'));
	}
}
