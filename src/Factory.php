<?php

namespace UIFactory;

use UIFactory\Component\Atom;
use UIFactory\Component\Molecule;
use UIFactory\Helper\ComponentDirector;

abstract class Factory
{
	use ComponentDirector;

	protected $atoms = [];
	protected $molecules = [];
	protected $organisms = [];

	/**
	 * @var array $themes List of available themes
	 */
	protected $themes = [];

	/**
	 * @var array $theme Current theme
	 */
	protected $theme;

	abstract public function button($echo = true) : Atom;
	abstract public function form($echo = true) : Molecule;
	abstract public function textField($echo = true) : Atom;
	// abstract public function select() : Atom\Select;
	// abstract public function checkbox() : Atom\Checkbox;

	public function __construct()
	{
		$this->initComponentList('atom');
		$this->initComponentList('molecule');
	}

	/**
	 * Add theme to the factory to immediately use it or just storing
	 *
	 * @uses Factory::useTheme() to use the given theme immediately
	 *
	 * @param Theme $theme Theme instance to add
	 * @param mixed $use Use the added theme immediately?
	 * @return Factory
	 */
	public function addTheme(Theme $theme, $use = false)
	{
		$this->themes[$theme->name] = $theme;

		if ($use) {
			$this->useTheme($theme->name);
		}

		return $this;
	}

	/**
	 * Use one of available theme by giving it a name
	 *
	 * @param string $name Available theme name
	 * @return Factory
	 */
	public function useTheme(string $name)
	{
		$this->theme = $this->themes[$name];
		return $this;
	}

	public function atom(string $name, $echo = true)
	{
		return $this->getComponent('atom', $name, $echo);
	}

	public function molecule(string $name, $echo = true)
	{
		return $this->getComponent('molecule', $name, $echo);
	}

	public function addAtom(Atom $atom)
	{
		$this->addComponent('atom', $atom);
		return $this;
	}

	public function addMolecule(Molecule $molecule)
	{
		$this->addComponent('molecule', $molecule);
		return $this;
	}
}