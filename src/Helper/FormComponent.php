<?php

namespace UIFactory\Helper;

use Exception;


trait FormComponent
{
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