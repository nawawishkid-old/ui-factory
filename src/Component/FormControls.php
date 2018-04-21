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
		return $this->props['required'] ? 'required' : '';
	}

	protected function disabled()
	{
		return $this->props['disabled'] ? 'disabled' : '';
	}

	protected function checked()
	{
		return $this->props['checked'] ? 'checked' : '';
	}

	protected function readonly()
	{
		return $this->props['readonly'] ? 'readonly' : '';
	}
}