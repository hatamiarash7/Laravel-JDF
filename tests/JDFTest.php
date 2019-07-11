<?php

namespace Hatamiarash7\JDF\Test;

use Hatamiarash7\JDF\Generator;
use Hatamiarash7\JDF\JDFServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class JDFTest extends Orchestra
{
	/**
	 * add the package provider
	 *
	 * @param $app
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
		return [JDFServiceProvider::class];
	}

	/** @test */
	public function test_functions()
	{
		$jdf = new Generator();

		$this->assertEquals(['2016', '8', '1'], $jdf->convertToGregorian("۱۳۹۵/۰۵/۱۱"));
	}
}

