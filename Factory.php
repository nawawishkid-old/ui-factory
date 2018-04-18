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

	abstract public function button(bool $echo = true) : AtomicUI;
	abstract public function form(bool $echo = true) : MolecularUI;
	abstract public function textField(bool $echo = true) : AtomicUI;
	// abstract public function select() : Atom\Select;
	// abstract public function checkbox() : Atom\Checkbox;

	public function addTheme(string $name, array $theme, $use = false)
	{
		$this->themes[$name] = $theme;

		if ($use) {
			$this->useTheme($name);
		}

		return $this;
	}

	public function useTheme(string $name)
	{
		$this->theme = $this->themes[$name];
		return $this;
	}
}