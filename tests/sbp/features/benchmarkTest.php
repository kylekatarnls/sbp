<?php

use Sbp\Wrapper\Sbp;
use Sbp\Wrapper\TestCase;

class BenchmarkTest extends TestCase
{
	public function testBenchmark()
	{
		$marker = 'Marker';
		Sbp::benchmark();
		Sbp::benchmark($marker);
		$content = Sbp::benchmark(Sbp::TEST_GET_BENCHMARK_HTML);
		$this->assertTrue(stripos($content, '<html') !== false);
	}
}
