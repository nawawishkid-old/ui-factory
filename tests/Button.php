<?php

use UIFactory\Component;

class Button extends Component
{
	protected $props = [
		'buttonContent' => 'Click me!',
		'buttonClass' => ''
	];

	public function markup($props) : string
	{
		return (
<<<HTML
$props->buttonBefore
<button class="btn btn-primary $props->buttonClass">
	$props->buttonContent
</button>
$props->buttonAfter
HTML
		);
	}
}