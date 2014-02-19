<?php

use sbp\sbp;
use sbp\sbpException;

class sbpTest extends \PHPUnit_Framework_TestCase
{
	public function parseTest()
	{
		$this->assertEquals(sbp::parse("ANameSpace\\BClass:CNameSpace\\DClass"), "class ANameSpace\\BClass extends CNameSpace\\DClass {}");
	}
}

?>