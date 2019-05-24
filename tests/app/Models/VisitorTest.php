<?php

namespace Rapid\Models;

require __DIR__ . '/../../../vendor/autoload.php';

use Rapids\Models\Visitors;
use PHPUnit\Framework\TestCase as PHPUnit;


class VisitorTest extends PHPUnit
{
	public function testCriacao()
	{
		$visitor = new Visitor();
		$this->assertEquals(1, 1);
	}


}
