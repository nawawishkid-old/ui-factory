<?php

namespace BootstrapUI;

use UIFactory\Components\FormControls;

class Select extends FormControls
{
	protected $props = [
		'label' => 'Bootstrap Select!',
	];

	protected $requiredProps = [
		'id', 'name'
	];

	public function __construct(array $props = [], $echo = 1)
	{
		parent::__construct($props, $echo);
	}

	protected function markup($props) : string
	{
		$select_class = $this->prop('select_class');
		$name = $this->prop('name');
		$id = $this->prop('id');
		$note = $this->prop('note');
		$label = $this->prop('label');
		$multiple = $this->prop('multiple') ? 'multiple' : '';

		return (
<<<HTML
<div class="form-group">
    <label for="$id">$label</label>
    <select $multiple class="form-control $select_class" id="$id" name="$name">
		<option>1</option>
		<option>2</option>
		<option>3</option>
		<option>4</option>
		<option>5</option>
    </select>
</div>
HTML
		);
	}
}