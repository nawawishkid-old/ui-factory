<?php

namespace UIFactory;

use UIFactory\Component\AtomicUI;
use UIFactory\Component\MolecularUI;

abstract class Factory
{
	protected $themes = [];
	protected $currentTheme = '';
	protected $theme = [
		'colors' => [
			'primary' => '',
			'secondary' => '',
			'accent' => ''
		]
	];

	abstract public function button() : AtomicUI;
	abstract public function form() : MolecularUI;
	abstract public function textField() : AtomicUI;
	// abstract public function select() : Atom\Select;
	// abstract public function checkbox() : Atom\Checkbox;

	public function addTheme(string $name, array $theme)
	{
		$this->themes[$name] = $theme;
	}

	public function useTheme(string $name)
	{
		$this->theme = $this->themes[$name];
	}
}