<?php

use UIFactory\Components\Base;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
	public function testCanBeInstantiated()
	{
		$this->assertInstanceOf(Base::class, new Base());
	}

	public function testCanAddHtmlMarkupStringByAnonymousFunction()
	{
		$expected = "<button>Click me!</button>";

		$base = new Base();
		$base->addMarkup(function($props) use ($expected) {
			return $expected;
		});

		$this->assertEquals($expected, $base->get());
	}
}