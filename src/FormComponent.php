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
		return $this->option('required') ? 'required' : '';
	}

	protected function disabled()
	{
		return $this->option('disabled') ? 'disabled' : '';
	}

	protected function checked()
	{
		return $this->option('checked') ? 'checked' : '';
	}

	protected function readonly()
	{
		return $this->option('readonly') ? 'readonly' : '';
	}
}