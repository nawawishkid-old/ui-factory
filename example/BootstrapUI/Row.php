<?php

namespace BootstrapUI;

use UIFactory\Component;

class Row extends Component
{
	protected $props = [
		'columns' => [], // Array of UI\Column
		'gutter' => true,
	];

	public function markup($props) : string
	{
		$gutter = $props->gutter ? '' : 'no-gutters';
		$columns = '';

		foreach ($props->columns as $column) {
			$columns .= is_string($column) ? $column : $column->get();
		}

		return (
<<<HTML
<div class="row $gutter $props->class">
	$columns
</div>
HTML
		);
	}
}