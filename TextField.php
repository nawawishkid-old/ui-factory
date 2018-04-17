<?php

namespace UIFactory\Component\Atom;

use UIFactory\Component\AtomicUI;

class TextField extends AtomicUI
{
	protected $name = 'default_name';

	/**
	 *
	 * @return string HTML markup
	 */
	// abstract protected function markup() : string;

	public function addName(string $name)
	{
		$this->name = $name;
	}
}