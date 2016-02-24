<?php

use Sbp\Wrapper\Sbp;
use Sbp\Wrapper\TestCase;

class ParseTest extends TestCase
{
    public function caseProvider()
	{
        $cases = array();

		foreach(scandir($dir = __DIR__ . '/../files/') as $file)
		{
			if(substr($file, 0, 1) !== '.' && is_file($file = $dir . $file))
			{
				$cases[] = array($file);
			}
		}

		return $cases;
	}

	public function testParse()
	{
		$content = explode("\n", sbp::testContent("<?\n__FILE . __DIR", __FILE__), 2);
		$content = $content[1];
		$this->assertTrue(trim($content) === var_export(__FILE__, true) . ' . ' . var_export(__DIR__, true), "__FILE . __DIR should be __FILE__ . __DIR__");
		$this->assertParse("ANameSpace\\BClass:CNameSpace\\DClass\n\t- \$var = 'a'", "class ANameSpace\\BClass extends CNameSpace\\DClass {\n\tprivate \$var = 'a';\n}");
	}

    /**
     * @dataProvider caseProvider
     */
	public function testParseFile($file)
	{
		$this->assertParseFile($file);
	}
}
