<?php

namespace BootstrapUI;

use UIFactory\Component;

class Button extends Component
{
	protected $props = [
		'label' => 'Bootstrap Button!',
		'class' => null
	];

	public function __construct(array $props = [], $echo = 1)
	{
		parent::__construct($props, $echo);
	}

	protected function markup($props) : string
	{
		return (
			'<button class="btn btn-primary ' . $props->class . '">' .
					$props->label . 
			'</button>'
		);
	}
}