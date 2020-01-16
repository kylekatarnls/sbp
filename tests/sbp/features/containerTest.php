<?php

use Sbp\Sbp;
use PHPUnit\Framework\TestCase;

class XBase
{
    public function hello()
    {
        return "Hello";
    }
}

class ContainerTest extends TestCase
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
