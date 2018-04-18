<?php

namespace UIFactory;

use UIFactory\Component\AtomicUI;
use UIFactory\Component\MolecularUI;

abstract class Factory
{
	protected $themes = [];
	protected $theme = null;

	abstract public function button(bool $echo = true) : AtomicUI;
	abstract public function form(bool $echo = true) : MolecularUI;
	abstract public function textField(bool $echo = true) : AtomicUI;
	// abstract public function select() : Atom\Select;
	// abstract public function checkbox() : Atom\Checkbox;

	public function addTheme(Theme $theme, bool $use = false)
	{
		$this->themes[$theme->name] = $theme;

		if ($use) {
			$this->useTheme($theme->name);
		}

		return $this;
	}

	public function useTheme(string $name)
	{
		$this->theme = $this->themes[$name];
		return $this;
	}
}