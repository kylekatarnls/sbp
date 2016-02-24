<?php

use Sbp\Wrapper\Sbp;
use Sbp\Wrapper\TestCase;

class BenchmarkTest extends TestCase
{
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
