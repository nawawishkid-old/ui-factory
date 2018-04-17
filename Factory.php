<?php

namespace UIFactory;

use UIFactory\Component\AtomicUI;

interface Factory
{
	public function button() : AtomicUI;
	// public function textField() : Atom\TextField;
	// public function select() : Atom\Select;
	// public function checkbox() : Atom\Checkbox;
}