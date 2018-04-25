<?php

namespace BootstrapUI;

use UIFactory\Components\FormControls;

class Radio extends FormControls
{
	protected $props = [
		// HTML attributes
		'readonly' => false,
		'required' => false,
		'disabled' => false,
		'checked' => false,
		'value' => null,
		'type' => 'radio',

		// Bootstrap
		'label' => 'Bootstrap Radio!',
		'helpText' => null,
		'inline' => false,
		'valid' => null,
		'invalid' => null,

		// Custom
		'inputClass' => null,
		'labelClass' => null,
		'wrapperClass' => null,
		'helpTextClass' => null,
		'feedbackClass' => null,
	];

	protected $requiredProps = [
		'name', 'id', 'value'
	];

	public function __construct(array $props = [], $echo = 1)
	{
		parent::__construct($props, $echo);
	}

	protected function markup($props) : string
	{
		$wrapperClass = $props->wrapperClass;
		$inputClass = $props->inputClass;
		$name = $props->name;
		$id = $props->id;
		$label = $props->label;
		$checked = $this->checked();
		$value = $props->value;
		$inline = $props->inline ? 'form-check-inline' : '';
		$disabled = $this->disabled();
		$type = $props->type;
		$valid = $this->feedback($props->valid, true);
		$invalid = $this->feedback($props->invalid);
		$labelClass = $props->labelClass;

		return (
<<<HTML
<div class="form-check $inline $disabled $wrapperClass">
	<input class="form-check-input $inputClass" 
			type="$type" 
			name="$name" 
			id="$id" 
			value="$value" 
			$checked
			$disabled
	>
	<label class="form-check-label $labelClass" for="$id">$label</label>
	$valid
	$invalid
</div>
HTML
		);
	}

	protected function feedback($text, $valid = false)
	{
		if (is_null($text)) {
			return '';
		}

		$class = $this->props['feedbackClass'];

		$valid = $valid ? 'valid' : 'invalid';
		return '<div class="' . $valid . '-feedback">' . $text . '</div>';
	}
}