<?php

namespace BootstrapUI;

use UIFactory\Components\FormControls;

class TextField extends FormControls
{
	protected $props = [
		// HTML attributes
		'readonly' => false,
		'required' => false,
		'disabled' => false,
		'placeholder' => 'Bootstrap TextField!',
		'value' => '',
		'type' => 'text',

		// Bootstrap
		'label' => 'Bootstrap TextField!',
		'size' => '',
		'helpText' => '',
		'valid' => '',
		'invalid' => '',
		'plaintext' => false,

		// Custom
		'inputClass' => '',
		'labelClass' => '',
		'wrapperClass' => '',
		'helpTextClass' => '',
		'feedbackClass' => '',
	];

	protected $requiredProps = [
		'id', 'name',
	];

	protected $requiredValidationProps = [
		'name' => ['type', 'string'],
		'size' => ['in', ['sm', 'lg']],
		'type' => ['in', ['text', 'password']]
	];

	public function __construct(array $props = [], $echo = 1)
	{	
		parent::__construct($props, $echo);
	}

	protected function markup($props) : string
	{
		$inputClass = $props->inputClass;
		$name = $props->name;
		$id = $props->id;
		$placeholder = $props->placeholder;
		$value = $props->value;
		$type = $props->type;

		$helpText = $this->helpText($props);
		$label = $this->label($props);
		$valid = $this->feedback($props, true);
		$invalid = $this->feedback($props);
		$readonly = $this->readonly();
		$required = $this->required();
		$disabled = $this->disabled();

		$plaintext = $props->plaintext ? '-plaintext' : '';
		$size = "form-control-" . $props->size;

		return (
<<<HTML
<div class="form-group">
	$label
	<input type="$type" 
			name="$name" 
			id="$id" 
			class="form-control$plaintext $size $inputClass" 
			placeholder="$placeholder"
			value="$value"
			$required
			$readonly
			$disabled
	>
	$helpText
	$valid
	$invalid
</div>
HTML
		);
	}

	protected function label($props)
	{
		if (empty($props->label)) {
			return '';
		}

		return "<label for=\"{$props->id}\" class=\"{$props->labelClass}\">{$props->label}</label>";
	}

	protected function helpText($props)
	{
		if (empty($props->helpText)) {
			return '';
		}

		$class = $props->helpTextClass;

		return (
			'<small class="form-text text-muted {$class}">' . $props->helpText . '</small>'
		);
	}

	protected function feedback($props, $valid = false)
	{
		$text = $valid ? $props->valid : $props->invalid;

		if (empty($text)) {
			return '';
		}

		$class = $props->feedbackClass;
		$valid = $valid ? 'valid' : 'invalid';

		return "<div class=\"{$valid}-feedback {$class}\">{$text}</div>";
	}
}