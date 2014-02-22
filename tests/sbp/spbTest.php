<?php

use sbp\sbp;
use sbp\sbpException;

class sbpTest extends \PHPUnit_Framework_TestCase
{
	protected function assertParse($from, $to, $message = null)
	{
		if(is_null($message))
		{
			$message = "sbp::parse(\"$from\") do not return \"$to\"";
		}
		$from = str_replace(array("\n", "\r", "\t", ' '), '', trim(sbp::parse($from)));
		$to = str_replace(array("\n", "\r", "\t", ' '), '', trim($to));
		return $this->assertTrue($from === $to, $message);
	}

	public function parseTest()
	{
		$this->assertParse("ANameSpace\\BClass:CNameSpace\\DClass", "class ANameSpace\\BClass extends CNameSpace\\DClass {}");
	}
}

?>