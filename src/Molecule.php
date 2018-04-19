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
	}

	public function atom(string $name, $get = true)
	{
		$atom = $this->getComponent('atom', $name, false);
		return $get ? $atom->get() : $atom;
	}
}