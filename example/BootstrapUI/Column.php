<?php

namespace BootstrapUI;

use UIFactory\Component;

class Column extends Component
{
	protected $props = [
		'default' => '',
		'sm' => '',
		'md' => '',
		'lg' => '',
		'xl' => '',
		'class' => '',
		'content' => 'Column content'
	];

	public function markup($props) : string
	{
		$default = empty($props->default) ? 'col' : "col-$props->default";
		$sm = empty($props->sm) ? $props->sm : "col-sm-$props->sm";
		$md = empty($props->md) ? $props->md : "col-md-$props->md";
		$lg = empty($props->lg) ? $props->lg : "col-lg-$props->lg";
		$xl = empty($props->xl) ? $props->xl : "col-xl-$props->xl";

		return (
<<<HTML
<div class="$default $sm $md $lg $xl $props->class">
	$props->content
</div>
HTML
		);
	}
}