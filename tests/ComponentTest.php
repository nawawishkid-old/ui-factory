<?php

require 'Button.php';

use PHPUnit\Framework\TestCase;

// class ComponentTest extends TestCase
// {
// 	private function button()
// 	{
// 		if (isset($this->button)) {
// 			return $this->button();
// 		}

// 		$this->button = new Button([], 0);
// 		return $this->button;
// 	}

// 	private function expectation($props)
// 	{
// 		$label = isset($props['label']) ? $props['label'] : 'Click me!';
// 		$class = isset($props['class']) ? $props['class'] : '';

// 		return (
// <<<HTML
// $props->buttonBefore
// <button class="btn btn-primary $props->buttonClass">
// 	$props->buttonContent
// </button>
// $props->buttonAfter
// HTML
// 		);
// 	}

// 	public function testCanReturnHtmlString()
// 	{
// 		$expected = $this->expectation(['label' => 'Click me!']);

// 		$this->assertEquals($expected, $this->button()->get());
// 	}

// 	public function testCanEditProperty()
// 	{
// 		$label = "Don't click me!";
// 		$expected = $this->expectation(['label' => $label]);
// 		$button = $this->button()
// 						->editProps([
// 							'label' => $label
// 						])
// 						->get();

// 		$this->assertEquals($expected, $button);
// 	}

// 	public function testCanEditPropertyMultipleTimes()
// 	{
// 		$label1 = "Don't click me!";
// 		$label2 = "Click me, please!";
// 		$expected = $this->expectation(['label' => $label2]);
// 		$button = $this->button()
// 						->editProps([
// 							'label' => $label1
// 						])
// 						->editProps([
// 							'label' => $label2
// 						])
// 						->get();

// 		$this->assertEquals($expected, $button);
// 	}

// 	public function testCanEditMultipleProperties()
// 	{
// 		$label = 'Hire me!';
// 		$class = 'my-custom-class';

// 		$expected = $this->expectation([
// 			'label' => $label,
// 			'class' => $class
// 		]);

// 		$button = $this->button()
// 						->editProps([
// 							'label' => $label,
// 							'class' => $class
// 						])
// 						->get();

// 		$this->assertEquals($expected, $button);
// 	}

// 	public function testCanPrependContent()
// 	{

// 	}
// }