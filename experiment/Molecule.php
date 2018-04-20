<?php

namespace UIFactory\Component;

use UIFactory\Theme;

abstract class Molecule extends Base
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

	public function __construct(array $props = [], Theme $theme = null, $echo = 1)
	{
		$this->initBaseList('atom');
		$this->attributes['class'] = self::CSS_CLASS_PREFIX . $this->getBaseNameFromClass($this);

		// $atoms = [];

		// foreach ($this->atoms as $name => $class) {
		// 	$atoms[$name] = new $class($theme, false);
		// }

		// $this->atoms = $atoms;

		parent::__construct($props, $theme, $echo);
	}

	public function editAtom(string $name, callable $callback)
	{
		$this->editBase('atom', $name, $callback);
		return $this;
	}

	public function atom(string $name, $get = true)
	{
		$atom = $this->getBase('atom', $name, false);
		return $get ? $atom->get() : $atom;
	}
}