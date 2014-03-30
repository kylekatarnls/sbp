<?php

use sbp\sbpException;

class sbp extends \sbp\sbp
{
	static public function testContent($from)
	{
		static::$lastParsedFile = $from;
		$content = self::parse(file_get_contents($from));
		static::$lastParsedFile = null;
		return $content;
	}
}

class sbpTest extends \PHPUnit_Framework_TestCase
{
	protected function assertParse($from, $to, $message = null)
	{
		if(is_null($message))
		{
			$message = "sbp::parse(\"$from\") do not return \"$to\"";
		}
		$explode = explode("\n", sbp::parse("<?\n".$from), 2);
		$from = str_replace(array("\n", "\r", "\t", ' '), '', trim(end($explode)));
		$to = str_replace(array("\n", "\r", "\t", ' '), '', trim($to));
		return $this->assertTrue($from === $to, $message);
	}

	protected function assertParseFile($from, $message = null)
	{
		if(is_null($message))
		{
			$message = "sbp::fileParse(\"$from\") do match the compiled file";
		}
		$to = str_replace(array("\n", "\r", "\t", ' '), '', trim(file_get_contents($from)));
		$from = str_replace(array("\n", "\r", "\t", ' '), '', trim(sbp::testContent(preg_replace('#^(.+)(/[^/]+)$#', '$1/.src$2', $from))));
		$to = preg_replace('#/\*.*\*/#U', '', $to);
		$from = preg_replace('#/\*.*\*/#U', '', $from);
		return $this->assertTrue($from === $to, $message);
	}

	public function testParse()
	{
		$this->assertParse("ANameSpace\\BClass:CNameSpace\\DClass\n\t- \$var = 'a'", "class ANameSpace\\BClass extends CNameSpace\\DClass {\n\tprivate \$var = 'a';\n}");
	}

	public function testParseFile()
	{
		foreach(scandir($dir = __DIR__ . '/files/') as $file)
		{
			if(substr($file, 0, 1) !== '.' && is_file($file = $dir . $file))
			{
				$this->assertParseFile($file);
			}
		}
	}
}

?>