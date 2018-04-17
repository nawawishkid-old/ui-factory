<?php

namespace UIFactory;

use UIFactory\Component\Atom;

interface Factory
{
	public function button() : Atom\Button;
	// public function textField() : Atom\TextField;
	// public function select() : Atom\Select;
	// public function checkbox() : Atom\Checkbox;
}