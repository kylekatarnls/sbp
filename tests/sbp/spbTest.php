<?php

use sbp\sbpException;

class sbp extends \sbp\sbp
{
	const TEST_GET_BENCHMARK_HTML = 'test-get-benchmark-html';
	const TEST_GET_LIST = 'test-get-list';

	static public function testFileContent($from)
	{
		return static::testContent(file_get_contents($from), $from);
	}

	static public function testContent($from, $file)
	{
		static::$lastParsedFile = $file;
		$content = self::parse($from);
		static::$lastParsedFile = null;
		return $content;
	}

	static public function benchmark($title = '')
	{
		static $list = null;
		if($title === static::TEST_GET_BENCHMARK_HTML)
		{
			return static::getBenchmarkHtml($list);
		}
		if($title === static::TEST_GET_LIST)
		{
			return $list;
		}
		return static::recordBenchmark($list, $title);
	}
}

class sbpTest extends \PHPUnit_Framework_TestCase
{
	const WRAP_LINES = 5;
	const IGNORE_BRACES = true;

	protected function assertParse($from, $to, $message = null)
	{
		if(is_null($message))
		{
			$message = "sbp::parse(\"$from\") do not return \"$to\"";
		}
		$explode = explode("\n", $parsed = sbp::parse("<?\n".$from), 2);
		$from = str_replace(array("\n", "\r", "\t", ' '), '', trim(end($explode)));
		$to = str_replace(array("\n", "\r", "\t", ' '), '', trim($to));
		return $this->assertTrue($from === $to, $message.", it return\"$parsed\"\n\n");
	}

	static protected function matchContent($from, $to)
	{
		$out = trim(file_get_contents($from));
		$in = trim(sbp::testFileContent($to));
		$to = str_replace(array("\n", "\r", "\t", ' '), '', $out);
		$from = str_replace(array("\n", "\r", "\t", ' '), '', $in);
		$to = preg_replace('#/\*.*\*/#U', '', $to);
		$from = preg_replace('#/\*.*\*/#U', '', $from);
		$trim = static::IGNORE_BRACES ? "{}\n\r\t " : "\n\r\t ";
		$lastDiffKey = -2 * static::WRAP_LINES;
		$lastPrintedKey = $lastDiffKey;
		if($from !== $to)
		{
			echo "\n";
			$in = preg_split('#\r\n|\r|\n#', preg_replace('#(\n[\t ]*)(\n[\t ]*)}([\t ]*)(?=\S)#', '$1}$2$3', $in));
			$out = preg_split('#\r\n|\r|\n#', preg_replace('#(\n[\t ]*)(\n[\t ]*)}([\t ]*)(?=\S)#', '$1}$2$3', $out));
			foreach($in as $key => $line)
			{
				if(preg_replace('#/\*.*\*/#U', '', trim($line, $trim)) === preg_replace('#/\*.*\*/#U', '', trim(isset($out[$key]) ? $out[$key] : '', $trim)))
				{
					if($key - $lastDiffKey === static::WRAP_LINES)
					{
						echo " [...]\n";
					}
					elseif($key - $lastDiffKey < static::WRAP_LINES && $key)
					{
						echo " ".str_replace("\t", '    ', $line)."\n";
						$lastPrintedKey = $key;
					}
				}
				else
				{
					for($i = max(0, $lastPrintedKey + 1, $key - static::WRAP_LINES); $i < $key; $i++)
					{
						echo " ".str_replace("\t", '    ', $in[$i])."\n";
					}
					echo "-".str_replace("\t", '    ', $line)."\n";
					echo "+".str_replace("\t", '    ', $out[$key])."\n";
					$lastDiffKey = $key;
					$lastPrintedKey = $key;
				}
			}
		}
		return ($from === $to);
	}

	protected function assertParseFile($from, $message = null, $keepMessage = null)
	{
		if(is_null($message))
		{
			$message = "sbp::fileParse(\"$from\") do match the compiled file";
		}
		if(is_null($keepMessage))
		{
			$keepMessage = "Normal PHP in compiled \"$from\" must be keeped intact if reparsed";
		}
		$to = preg_replace('#^(.+)(/[^/]+)$#', '$1/.src$2', $from);
		$this->assertTrue(static::matchContent($from, $to), $message);
		$this->assertTrue(static::matchContent($from, $from), $keepMessage);
	}

	public function testParse()
	{
		$content = explode("\n", sbp::testContent("<?\n__FILE . __DIR", __FILE__), 2);
		$content = $content[1];
		$this->assertTrue(trim($content) === var_export(__FILE__, true) . ' . ' . var_export(__DIR__, true), "__FILE . __DIR should be __FILE__ . __DIR__");
		$this->assertParse("ANameSpace\\BClass:CNameSpace\\DClass\n\t- \$var = 'a'", "class ANameSpace\\BClass extends CNameSpace\\DClass {\n\tprivate \$var = 'a';\n}");
	}

	public function testPlugin()
	{
		sbp_add_plugin('jQuery', '$(', 'new jQuery(');
		$this->assertParse("\$result = \$('#element')->animate({\n\tleft = 400\n\ttop = 200\n});", "\$result = new jQuery('#element')->animate(array(\n\t'left' => 400,\n\t'top' => 200\n));");
		sbp_remove_plugin('jQuery');
		$this->assertParse("\$result = \$('#element')->animate({\n\tleft = 400\n\ttop = 200\n});", "\$result = \$('#element')->animate(array(\n\t'left' => 400,\n\t'top' => 200\n));");
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

	public function testBenchmark()
	{
		$marker = 'Marker';
		sbp::benchmark();
		sbp::benchmark($marker);
		$content = sbp::benchmark(sbp::TEST_GET_BENCHMARK_HTML);
		$this->assertTrue(stripos($content, '<html') !== false);
		//$this->assertTrue(strpos($content, $marker) !== false);
	}
}

?>