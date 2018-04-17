<?php

namespace UIFactory;

use UIFactory\Component\AtomicUI;
use UIFactory\Component\MolecularUI;

abstract class Factory
{
	abstract public function button() : AtomicUI;
	abstract public function form() : MolecularUI;
	// abstract public function textField() : Atom\TextField;
	// abstract public function select() : Atom\Select;
	// abstract public function checkbox() : Atom\Checkbox;
}