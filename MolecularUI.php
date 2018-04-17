<?php

namespace UIFactory\Component;

abstract class MolecularUI extends CommonUI
{
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

	public function __construct(array $theme)
	{
		parent::__construct($theme);

		$atoms = [];

		foreach ($this->atoms as $name => $class) {
			$atoms[$name] = new $class($theme);
		}

		$this->atoms = $atoms;
	}

	public function editAtom(string $name, callable $callback)
	{
		$this->atoms[$name] = call_user_func_array($callback, [$this->atoms[$name]]);
		return $this;
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

		return $atom instanceof AtomicUI ? ($make === true ? $atom->make() : $atom)
										 : $atom;
	}
}