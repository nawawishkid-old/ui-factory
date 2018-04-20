<?php

namespace UIFactory\Component;


abstract class FormControls extends Base
{
	/**
	 * Abstract function for returning HTML markup of this component
	 *
	 * @return string HTML markup
	 */
	// abstract protected function markup() : string;

	protected function required()
	{
		// propExists($prop); // search prop value, not key
		return $this->prop('required') ? 'required' : '';
	}

	protected function disabled()
	{
		return $this->prop('disabled') ? 'disabled' : '';
	}

	protected function checked()
	{
		return $this->prop('checked') ? 'checked' : '';
	}

	protected function readonly()
	{
		return $this->prop('readonly') ? 'readonly' : '';
	}
}