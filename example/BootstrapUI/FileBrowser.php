<?php

namespace BootstrapUI;

use UIFactory\Components\FormControls;

class FileBrowser extends FormControls
{
	protected $props = [
		'label' => 'Choose file'
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
		$input_class = $this->prop('input_class');
		$id = $this->prop('id');
		$name = $this->prop('name');
		$label = $this->prop('label');

		return (
<<<HTML
<div class="custom-file">
	<input type="file" id="$id" class="custom-file-input $input_class">
	<label class="custom-file-label" for="$id">$label</label>
</div>
HTML
		);
	}
}