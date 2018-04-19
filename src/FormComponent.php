<?php

namespace UIFactory\Helper;

use Exception;
use UIFactory\Theme;
use UIFactory\Helper\ComponentDirector;

trait FormComponent
{
	use ComponentDirector;

	protected function required()
	{
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