<?php

use Sbp\Wrapper\Sbp;
use Sbp\Wrapper\TestCase;

class BenchmarkTest extends TestCase
{
	public function testBenchmark()
	{
		$marker = 'Marker';
		Sbp::benchmark();
		usleep(200);
		Sbp::benchmark($marker);
		usleep(200);
		$content = Sbp::benchmark(Sbp::TEST_GET_BENCHMARK_HTML);
		$this->assertTrue(stripos($content, '<html') !== false);
		$this->assertTrue(strpos($content, $marker) !== false);
	}
}
