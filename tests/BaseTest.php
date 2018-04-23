<?php

use UIFactory\Components\Base;
use PHPUnit\Framework\TestCase;

/**
 * Test only methods in Base class, not including methods of UIFactory\Component
 */
class BaseTest extends TestCase
{
	public function testCanBeInstantiated()
	{
		$this->assertInstanceOf(Base::class, new Base());
	}

	public function testCanAddHtmlMarkupStringUsingAnonymousFunction()
	{
		$expected = "<button>Click me!</button>";

		$base = new Base();
		$base->addMarkup(function ($props) use ($expected) {
			return $expected;
		});

		$this->assertEquals($expected, $base->get());
	}

	public function testCanAddHtmlMarkupStringUsingStringCallback()
	{
		$expected = "<button>Click me!</button>";

		function button() {
			return "<button>Click me!</button>";
		}

		$base = new Base();
		$base->addMarkup('button');

		$this->assertEquals($expected, $base->get());
	}

	public function testCanAddProps()
	{
		$label = "Don't click me!";
		$base = new Base();
		$base->addMarkup(function ($props) {
			return "<button>$props->label</button>";
		})->addProps([
			'label' => $label
		]);

		$this->assertEquals($label, $base->label);
	}

	public function testCanAddMultipleProps()
	{
		$label = "Don't click me!";
		$class = "btn btn-primary";

		$base = new Base();
		$base->addMarkup(function ($props) {
			return "<button class=\"$props->class\">$props->label</button>";
		})->addProps([
			'label' => $label,
			'class' => $class
		]);

		$this->assertEquals($label, $base->label);
		$this->assertEquals($class, $base->class);
	}
}