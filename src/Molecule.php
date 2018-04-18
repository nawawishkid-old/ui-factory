<?php

namespace UIFactory\Component;

use UIFactory\Theme;

abstract class Molecule extends Common
{
	const CSS_CLASS_PREFIX = 'uif-m-';

	protected $componentID;
	/**
	 * Array of atom ['name' => AtomClass::class] inside this molecule
	 *
	 * @var array $atoms
	 */
	protected $atoms = [];

	/**
	 * HTML markup
	 *
	 * @return string HTML markup
	 */
	// abstract protected function markup() : string;

	public function __construct(Theme $theme, $echo = true)
	{
		$this->initComponentList('atom');
		$this->attributes['class'] = self::CSS_CLASS_PREFIX . $this->getComponentNameFromClass($this);

		// $atoms = [];

		// foreach ($this->atoms as $name => $class) {
		// 	$atoms[$name] = new $class($theme, false);
		// }

		// $this->atoms = $atoms;

		parent::__construct($theme, $echo);
	}

	public function editAtom(string $name, callable $callback)
	{
		$this->editComponent('atom', $name, $callback);
		return $this;
		// $this->atoms[$name] = call_user_func_array($callback, [$this->atoms[$name]]);
		// return $this;
	}

	public function atom(string $name, $make = true)
	{
		$atom = $this->getComponent('atom', $name, false);
		return $make ? $atom->make() : $atom;
	}

	protected function getAtom($atom_name = null, $make = true)
	{
		if (is_string($atom_name)) {
			return $this->getSingleAtom($atom_name, $make);
		}

		$atoms = [];

		if (is_array($atom_name)) {
			foreach ($atom_name as $name) {
				$atoms[$name] = $this->getSingleAtom($name, $make);
			}

			return $atoms;
		}

		foreach ($this->atoms as $name => $class) {
			$atoms[$name] = $this->getSingleAtom($name, $make);
		}

		return $atoms;
	}

	private function getSingleAtom($atom_name, $make = true)
	{
		$atom = $this->atoms[$atom_name];
		$atom = isset($atom) ? $atom : null;

		return $atom instanceof Atom ? ($make === true ? $atom->make() : $atom)
										 : $atom;
	}
}