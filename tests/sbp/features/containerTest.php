<?php

use Sbp\Sbp;

class XBase
{
    public function hello()
    {
        return "Hello";
    }
}

class ContainerTest extends \PHPUnit_Framework_TestCase
{
	public function testContainer()
	{
        Sbp::execute(__DIR__.'/../container/foo.sbp');
        $foo = new XFoo();
		$this->assertSame(
            $foo->hello(),
            "Hello foo"
        );
	}
}
